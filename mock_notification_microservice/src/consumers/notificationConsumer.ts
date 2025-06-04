import { subscribeToNotifications } from '../clients/queueClient';
import { processNotificationWithRetry } from '../services/notificationService';

const NOTIFICATION_CHANNEL = process.env.NOTIFICATION_CHANNEL || 'notifications';

export async function startNotificationConsumer() {
    console.log('startNotificationConsumer called');
    await subscribeToNotifications(NOTIFICATION_CHANNEL, async (message) => {
    console.log('Raw message received:', message);
    try {
        const payload = JSON.parse(message);
        console.log('Parsed payload:', payload);
        try {
            await processNotificationWithRetry(payload);
        } catch (error) {
            console.error('Failed to process notification after retries:', error);
        }
    } catch (e) {
        console.error('JSON parse error:', e, message);
    }
  });
}

startNotificationConsumer().catch(console.error);