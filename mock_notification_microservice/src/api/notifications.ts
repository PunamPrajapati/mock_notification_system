import { FastifyPluginAsync } from 'fastify'
import axios from 'axios'

const laravelBaseUrl = process.env.LARAVEL_API_URL || 'http://127.0.0.1:8000'
const notifications: FastifyPluginAsync = async (app) => {
    app.get('/notifications/recent', async (req, res) => {
        try {
        
        const response = await axios.get(`${laravelBaseUrl}/notifications/recent`)
        return response.data
        } catch (error) {
        app.log.error(error)
        return res.status(500).send({ error: 'Failed to fetch recent notifications from Laravel API.' })
        }
    })

    app.get('/notifications/summary', async (req, res) => {
        try {
            const response = await axios.get(`${laravelBaseUrl}/notifications/summary`)
            return response.data
            } catch (error) {
            app.log.error(error)
            return res.status(500).send({ error: 'Failed to fetch notification summary from Laravel API.' })
        }
    })
}

export default notifications