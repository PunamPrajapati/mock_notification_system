import Fastify from 'fastify'
import dotenv from 'dotenv'
dotenv.config()
import notifications from './api/notifications';

const app = Fastify()

app.register(notifications);

export default app