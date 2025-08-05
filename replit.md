# YouTube Quiz Application

## Overview

This is a React-based web application featuring an interactive quiz about starting a YouTube channel. The application uses a modern tech stack with React, TypeScript, Tailwind CSS, and shadcn/ui components for a polished user interface. The backend is built with Express.js and includes Drizzle ORM for database operations with PostgreSQL.

## System Architecture

### Frontend Architecture
- **Framework**: React 18 with TypeScript
- **Styling**: Tailwind CSS with shadcn/ui component library
- **Routing**: Wouter for client-side routing
- **State Management**: TanStack Query for server state management
- **Animations**: Framer Motion for smooth transitions and animations
- **Build Tool**: Vite for fast development and optimized builds

### Backend Architecture
- **Runtime**: Node.js with Express.js framework
- **Language**: TypeScript with ES modules
- **Database ORM**: Drizzle ORM configured for PostgreSQL
- **Session Storage**: PostgreSQL sessions using connect-pg-simple
- **Development**: Hot reload with tsx for server-side development

### UI Component System
- **Design System**: shadcn/ui with Radix UI primitives
- **Theme**: Neutral color scheme with CSS variables for theming
- **Responsive Design**: Mobile-first approach with Tailwind breakpoints
- **Component Structure**: Modular components in `/components/ui/` directory

## Key Components

### Quiz Interface
- Interactive multi-step quiz with progress tracking
- Animated question transitions using Framer Motion
- Progress bar showing completion status
- Multiple choice answers with visual feedback

### Database Schema
- User management with username/password authentication
- Drizzle schema definition in `shared/schema.ts`
- PostgreSQL as the primary database with Neon serverless integration

### Storage Layer
- Abstract storage interface for CRUD operations
- In-memory storage implementation for development
- Database-backed storage ready for production deployment

## Data Flow

1. **Client-Side**: React components manage local state and user interactions
2. **API Layer**: Express.js handles HTTP requests with `/api` prefix
3. **Storage**: Drizzle ORM abstracts database operations
4. **Database**: PostgreSQL stores persistent data

## External Dependencies

### UI/UX Libraries
- **@radix-ui/***: Accessible component primitives
- **framer-motion**: Animation and transition library
- **lucide-react**: Icon library
- **class-variance-authority**: Type-safe component variants

### Database & Backend
- **@neondatabase/serverless**: PostgreSQL serverless driver
- **drizzle-orm**: TypeScript ORM
- **connect-pg-simple**: PostgreSQL session store

### Development Tools
- **Vite**: Build tool and dev server
- **@replit/vite-plugin-***: Replit-specific development plugins
- **tsx**: TypeScript execution for development

## Deployment Strategy

### Development Environment
- Vite dev server serves the frontend on the client directory
- Express server handles API routes and serves static files in production
- Hot module replacement enabled for rapid development

### Production Build
- Frontend builds to `dist/public` directory
- Backend compiles to `dist/index.js` using esbuild
- Environment variables manage database connections and configurations

### Environment Configuration
- `NODE_ENV` determines development vs production behavior
- `DATABASE_URL` required for PostgreSQL connection
- Drizzle migrations stored in `./migrations` directory

## Changelog
- July 03, 2025. Initial setup with YouTube quiz functionality
- July 03, 2025. Updated quiz to redirect to GHL funnel instead of recording answers

## User Preferences

Preferred communication style: Simple, everyday language.