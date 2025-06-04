## Running Laravel, Node.js, and Redis Locally (Without Docker)

### Prerequisites

- [PHP](https://www.php.net/) (version 8.0 or above recommended)
- [Composer](https://getcomposer.org/)
- [Node.js & npm](https://nodejs.org/)
- [Redis](https://redis.io/) (install locally or use a cloud/remote instance)
- [MySQL](https://www.mysql.com/) (install locally or use a remote instance)
- [VS Code](https://code.visualstudio.com/) (recommended IDE)

### 1. Clone the Repository

```sh
git clone https://github.com/PunamPrajapati/mock_notification_system.git
cd mock_notification_system
```

### 2. Set Up Environment Variables

- Copy `.env.example` to `.env` in both Laravel, Node.js and main projects.
- Update database, Redis, and other settings as needed for your local environment.

### 3. Install Dependencies

**Laravel:**
```sh
cd mock_notification_api
composer install
php artisan key:generate # Generate key
php artisan migrate   # Run migrations
```

**Node.js:**
```sh
cd ../mock_notification_microservice
npm install
```

### 4. Start Redis Server

Start Redis using your system’s service manager or directly:
```sh
docker compose exec redis redis-cli
```

### 5. Start the Servers

**Laravel (from `mock_notification_api`):**
```sh
php artisan serve --host=127.0.0.1 --port=8000
```

**Node.js (from `mock_notification_microservice`):**
```sh
npm run dev
```

### 6. Access the Applications

- Laravel API: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- Node.js Service: [http://127.0.0.1:3000](http://127.0.0.1:3000) (or as configured)
- Redis: Running locally on port 6379

### 7. Tips

- Open multiple terminals in VS Code for running each service.
- If you make changes to `.env` files, restart the servers.
- Ensure MySQL and Redis services are running before starting Laravel or Node.js.
- For troubleshooting, check logs/output in VS Code terminals.

---

## API Documentation

The detailed API documentation is provided in the attached PDF file sent with this email.  
Please refer to the attached document for complete API endpoints, request/response formats, and usage instructions.

---

## How to Test Notification Message Consumption

You can manually test the message consumption workflow in this project by publishing a notification to Redis and running the Node.js consumer.

### 1. Start the Message Consumer

In a new terminal, navigate to the **Node.js microservice directory** and run:
```sh
cd ../mock_notification_microservice
npm run start:consumer
```
This command starts the notification consumer, which listens for messages on the configured Redis channel (e.g., `notifications`).

### 2. Publish a Notification Message to Redis

Open another terminal and use the Redis CLI to publish a test notification message.  
``Let us assume when notification request api is called in laravel, below notification message is published``
For example:
```sh
docker compose exec redis redis-cli
PUBLISH notifications '{"id": 1, "user_id": 1, "message": "This is just for testing notification store api", "status": "processed", "created_at": "2025-06-01T11:21:59.000000Z", "updated_at": "2025-06-01T12:22:41.000000Z"}'
```
> Make sure you use single quotes around the JSON message.

### 3. Observe the Consumer Output

If everything is set up correctly, you should see the consumer terminal display the received notification message.

---

**Tip:**  
- Ensure Redis is running and accessible.
- The channel name in the publish command (`notifications`) should match the one your consumer is subscribed to.