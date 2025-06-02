import { FastifyPluginAsync } from "fastify";
import { db } from '../lib/db';

const summaryNotification: FastifyPluginAsync = async (app) => {
  app.get('/notifications/summary', async (req, res) => {
    const [rows] = await db.query(`
      SELECT 
        COUNT(*) AS total,
        SUM(status = 'pending') AS pending,
        SUM(status = 'processed') AS success,
        SUM(status = 'failed') AS failed
      FROM notifications
    `)

    return rows
  })
}

export default summaryNotification