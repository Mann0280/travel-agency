# ZUBEEE Tours & Travels

A modern, responsive travel agency website built with Laravel 12, featuring beautiful UI design and comprehensive travel package management.

## 🚀 Features

- **Modern UI/UX**: Clean, responsive design with Tailwind CSS
- **User Authentication**: Complete login/register system
- **Travel Packages**: Browse and search travel destinations
- **Agency Management**: View agency details and packages
- **Account Management**: User profile, bookings, and settings
- **Mobile-First**: Optimized for all devices with mobile navigation
- **Search Functionality**: Find packages by destination and departure city

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS
- **Database**: SQLite (configurable)
- **JavaScript**: Vanilla JS with modern ES6+ features
- **Icons**: Heroicons (SVG)

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM (for asset compilation)
- SQLite or MySQL database

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd travel-agency
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

   Visit `http://127.0.0.1:8000` in your browser.

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/     # All controllers
│   ├── Models/              # Eloquent models
│   ├── Helpers/             # Custom helper classes
│   └── Providers/           # Service providers
├── resources/
│   ├── views/               # Blade templates
│   │   ├── layouts/         # Layout templates
│   │   ├── auth/           # Authentication views
│   │   └── ...              # Page views
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript files
├── routes/
│   └── web.php             # Web routes
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
└── public/                 # Public assets
```

## 🔧 Available Routes

| Method | Route | Controller | Description |
|--------|-------|------------|-------------|
| GET | `/` | HomeController@index | Homepage |
| GET | `/search` | SearchController@index | Search packages |
| GET | `/agency` | AgencyController@index | Agency overview |
| GET | `/agency/{package}` | AgencyController@index | Package details |
| GET | `/agency/{agency}/{package}` | AgencyDetailsController@show | Detailed package view |
| GET | `/account` | AccountController@index | User account |
| GET | `/login` | AuthController@showLogin | Login page |
| GET | `/register` | AuthController@showRegister | Registration page |

## 🎨 UI Features

- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **Modern Navigation**: Sticky header with mobile hamburger menu
- **Bottom Navigation**: WhatsApp-style mobile navigation bar
- **Smooth Scrolling**: Enhanced scrolling animations
- **Interactive Elements**: Hover effects and transitions
- **Color Scheme**: Professional green and gold theme

## 🧪 Testing

Run the test suite:

```bash
php artisan test
```

## 📱 Mobile Optimization

- Dedicated mobile header layout
- Bottom navigation bar for quick access
- Touch-friendly interface elements
- Optimized performance for mobile networks

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Support

For support, email support@zubee.com or join our Discord community.

---

**Built with ❤️ using Laravel**
