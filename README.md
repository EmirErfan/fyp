# Centralized Web-Based System for Stress Induction Methods (StressLab)

A rigorous, validated suite of cognitive stress protocols designed for psychological research. This platform digitizes established stress induction tasks, providing real-time analytics, automated scoring, and secure institutional access management.

Developed as a Final Year Project at **UiTM Kampus Jasin**.

---

## 🚀 Features

### Validated Stress Protocols

- **Stroop Color and Word Test:** Measures cognitive interference and selective attention with automated reaction time tracking.
- **Montreal Imaging Stress Task (MIST):** Induces stress through time-pressured arithmetic tasks with dynamic difficulty adjustment.
- **Trier Social Stress Test (TSST):** Standardized serial subtraction tasks under evaluative observation conditions.

### Additional Features

- **Researcher Access Control:** Secure, role-based institutional access requiring admin approval and automated credential provisioning.
- **Analytics Dashboard:** Real-time monitoring of participant response metrics, reaction times, and accuracy scores.
- **Secure Data Handling:** Complete confidentiality for participant data, compliant with standard academic ethics requirements.

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel (PHP) |
| Database | MariaDB / MySQL |
| Frontend | Blade Templating, HTML5, CSS3, JavaScript |
| Environment | Docker (via Laravel Sail) |

---

## 💻 Installation & Setup

This project uses Laravel Sail for a seamless Docker development environment. Ensure you have [Docker Desktop](https://www.docker.com/products/docker-desktop) installed and running before proceeding.

**1. Clone the repository**

```bash
git clone https://github.com/EmirErfan/fyp.git
cd stress-system
```

**2. Install Composer dependencies**

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

**3. Environment Setup**

Copy the `.env` example file and configure your local database and mail settings.

```bash
cp .env.example .env
```

> **Note:** Ensure your `.env` contains valid SMTP credentials (e.g., Gmail App Password) for the researcher access approval email flow to function.

**4. Start Laravel Sail**

```bash
./vendor/bin/sail up -d
```

**5. Generate Application Key**

```bash
./vendor/bin/sail artisan key:generate
```

**6. Run Migrations & Seeders**

```bash
./vendor/bin/sail artisan migrate --seed
```

**7. Clear Configuration Cache**

If you modify your `.env` file (especially mail settings), always clear the cache:

```bash
./vendor/bin/sail artisan config:clear
```

The application will now be accessible at `http://localhost`.

---

## 👨‍💻 Author

**Ahmad Emir Erfan**
- GitHub: [@EmirErfan](https://github.com/EmirErfan)

---

## 📄 License

This project is proprietary and developed for academic and institutional research purposes. All rights reserved.
