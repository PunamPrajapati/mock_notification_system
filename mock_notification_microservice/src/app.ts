import Fastify from 'fastify'
import notifications from './api/notifications';
import dotenv from 'dotenv'
dotenv.config()

const app = Fastify()

app.register(notifications);

export default app