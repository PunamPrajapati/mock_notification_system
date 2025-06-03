import { subscribeToNotifications, redisClient } from './queueClient';
import { createClient } from 'redis';

async function main() {
  await subscribeToNotifications('test_channel', (message) => {
    console.log('Received message:', message);
    // Optionally quit after first message
    redisClient.quit();
    publisher.quit();
  });

  const publisher = createClient();
  await publisher.connect();
  setTimeout(() => {
    publisher.publish('test_channel', 'Hello from test!');
  }, 1000);
}

main().catch(console.error);