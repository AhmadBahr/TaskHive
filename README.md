# TaskHive - Laravel Kanban Board Application

A modern, full-featured Kanban board application built with Laravel 11, Livewire, and Tailwind CSS.

## 🚀 Features

### ✅ Implemented Features
- **User Authentication** - Laravel Breeze with email verification
- **Board Management** - Create, edit, and manage multiple boards
- **Column Management** - Customizable columns with WIP limits
- **Task Management** - Full CRUD operations for tasks
- **Drag & Drop** - Move tasks between columns with visual feedback
- **Priority System** - Low, Medium, High, and Urgent priorities with color coding
- **Task Assignment** - Assign tasks to team members
- **Due Dates** - Set and track task due dates
- **Activity Logging** - Track task movements and changes
- **Responsive Design** - Mobile-friendly interface
- **Modern UI** - Clean, intuitive design with Tailwind CSS

### 🔄 In Progress / Planned Features
- **WIP Limit Enforcement** - Visual indicators and warnings
- **Advanced Search & Filtering** - Find tasks quickly
- **Bulk Operations** - Multi-task management
- **Board Sharing** - Collaborate with team members
- **Notifications** - Real-time updates and alerts
- **API Endpoints** - REST API for mobile/frontend integration
- **Testing Suite** - Comprehensive test coverage
- **Performance Optimization** - Caching and query optimization
- **Accessibility** - ARIA labels and keyboard navigation

## 🛠️ Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Livewire 3, Alpine.js, Tailwind CSS
- **Database**: PostgreSQL with UUID primary keys
- **Authentication**: Laravel Breeze
- **Build Tool**: Vite
- **Styling**: Tailwind CSS with custom components

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- PostgreSQL 12+
- npm or yarn

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd TaskHive
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install --legacy-peer-deps
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   Update your `.env` file with PostgreSQL credentials:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=task_hive
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   Open your browser and navigate to `http://localhost:8000`

## 📁 Project Structure

```
TaskHive/
├── app/
│   ├── Http/Controllers/     # API and web controllers
│   ├── Livewire/            # Livewire components
│   ├── Models/              # Eloquent models
│   └── Policies/            # Authorization policies
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   └── views/              # Blade templates
└── routes/
    ├── api.php             # API routes
    └── web.php             # Web routes
```

## 🎯 Core Models

### Board
- Represents a project or workspace
- Contains multiple columns and tasks
- Belongs to a user (owner)

### Column
- Represents workflow stages (e.g., To Do, In Progress, Done)
- Has a position and optional WIP limit
- Contains multiple tasks

### Task
- Individual work items
- Has title, description, priority, assignee, due date
- Belongs to a board and column
- Has position within column

### TaskActivity
- Logs all task movements and changes
- Tracks from/to columns and notes
- Provides audit trail

## 🔧 Key Components

### BoardKanban (Livewire)
- Main kanban board interface
- Handles drag & drop functionality
- Manages task display and interactions

### TaskModal (Livewire)
- Create and edit tasks
- Form validation and submission
- Real-time updates

### BoardsList (Livewire)
- Display user's boards
- Board creation and management

## 🎨 UI Components

### Priority Badges
- Color-coded priority indicators
- Green (Low), Yellow (Medium), Orange (High), Red (Urgent)

### WIP Limits
- Visual indicators for column capacity
- Warning and exceeded states

### Drag & Drop
- Smooth task movement between columns
- Visual feedback during drag operations

## 🔐 Security Features

- **Authentication**: Laravel Breeze with email verification
- **Authorization**: Policy-based access control
- **CSRF Protection**: Built-in Laravel CSRF tokens
- **Input Validation**: Comprehensive form validation
- **SQL Injection Protection**: Eloquent ORM with parameter binding

## 📱 Responsive Design

- Mobile-first approach
- Responsive grid layouts
- Touch-friendly drag & drop
- Optimized for all screen sizes

## 🚀 Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Building Assets
```bash
# Development
npm run dev

# Production
npm run build
```

## 🔄 API Endpoints

### Boards
- `GET /api/boards` - List user's boards
- `POST /api/boards` - Create new board
- `GET /api/boards/{id}` - Get specific board
- `PUT /api/boards/{id}` - Update board
- `DELETE /api/boards/{id}` - Delete board

### Tasks
- `GET /api/boards/{id}/tasks` - List board tasks
- `POST /api/boards/{id}/tasks` - Create new task
- `GET /api/tasks/{id}` - Get specific task
- `PUT /api/tasks/{id}` - Update task
- `DELETE /api/tasks/{id}` - Delete task
- `PATCH /api/tasks/{id}/move` - Move task between columns

## 🐛 Troubleshooting

### Common Issues

1. **Assets not loading**
```bash
   npm run build
php artisan view:clear
```

2. **Database connection issues**
   - Check PostgreSQL is running
   - Verify database credentials in `.env`
   - Run `php artisan migrate:status`

3. **Permission errors**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel Framework
- Livewire Team
- Tailwind CSS
- Alpine.js
- All contributors and testers

## 📞 Support

For support and questions:
- Create an issue on GitHub
- Check the documentation
- Review the troubleshooting section

---

**TaskHive** - Organize your work, boost your productivity! 🚀