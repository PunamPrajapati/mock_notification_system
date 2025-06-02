import { FastifyPluginAsync } from 'fastify'
import { db } from '../lib/db';

const recentNotification: FastifyPluginAsync = async (app) => {
    app.get('/notifications/recent', async (req, res) => {
        const [rows] = await db.query(
            `SELECT * FROM notifications ORDER BY created_at DESC LIMIT 10`
            )
            return  rows
    })
}

export default recentNotification