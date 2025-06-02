import Fastify from 'fastify'
import recentNotification from './api/recentNotification';
import summaryNotification from './api/summaryNotification';
import dotenv from 'dotenv'
dotenv.config()

const app = Fastify()

app.register(recentNotification);
app.register(summaryNotification);

export default app