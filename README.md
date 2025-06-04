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

**Note:**  
You can still use Docker for convenience, but the above instructions help you run each service natively for local development and debugging.