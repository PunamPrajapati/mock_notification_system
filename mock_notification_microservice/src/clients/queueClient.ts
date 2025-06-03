import { createClient } from 'redis';

const redisUrl = process.env.REDIS_URL || 'redis://localhost:6379';
export const redisClient = createClient({ url: redisUrl });

redisClient.on('error', (err) => {
  console.error('Redis Client Error', err);
});

export async function subscribeToNotifications(channel: string, handler: (message: string) => void) {
  await redisClient.connect();
  console.log('Connected to Redis, subscribing...');
  await redisClient.subscribe(channel, (message) => {
    console.log('Received message:', message);
    handler(message);
  });
}