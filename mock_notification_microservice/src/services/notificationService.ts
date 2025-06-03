import axios from 'axios';

const LARAVEL_API_URL = process.env.LARAVEL_API_URL || 'http://localhost:8000';
const MAX_RETRIES = 3;
const BASE_DELAY_MS = 1000;

// Simulate sending a notification (e.g., by logging)
function sendNotification(payload: any) {
  console.log('Sending notification:', payload);
  // Simulate possible random failure (for demonstration)
  if (Math.random() < 0.3) throw new Error('Simulated send failure');
}

// Update notification status in Laravel
async function updateNotificationStatus(id: number, status: string) {
  await axios.patch(`${LARAVEL_API_URL}/api/notifications/${id}`, {
    status: status,
  });
}

// Retry logic with exponential backoff
export async function processNotificationWithRetry(payload: any, attempt = 1): Promise<void> {
  try {
    sendNotification(payload);
    await updateNotificationStatus(payload.id, 'processed');
    console.log('Notification processed successfully:', payload.id);
  } catch (error) {
    if (attempt < MAX_RETRIES) {
      const delay = BASE_DELAY_MS * Math.pow(2, attempt - 1);
      console.warn(`Attempt ${attempt} failed. Retrying in ${delay}ms...`, error);
      await new Promise((resolve) => setTimeout(resolve, delay));
      return processNotificationWithRetry(payload, attempt + 1);
    }
    console.error('Max retries reached for notification:', payload.id, error);
    await updateNotificationStatus(payload.id, 'failed');
    console.log('Notification marked as failed:', payload.id);
  }
}