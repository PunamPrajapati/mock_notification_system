import Fastify from 'fastify'
import recentNotification from './api/recentNotification';
import summaryNotification from './api/summaryNotification';

const app = Fastify()

app.register(recentNotification);
app.register(summaryNotification);

export default app