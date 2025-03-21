# Income Tax Calculator

A PHP-based UK tax calculation system that determines income tax based on predefined tax bands.

## Overview

This system calculates UK income tax across multiple tax bands, providing both gross and net salary figures on annual and monthly bases.

## Technology Stack

- **PHP**: 8.2+
- **Framework**: Symfony 7.2
- **Database**: MySQL (via Doctrine ORM)
- **Infrastructure**: Docker, Docker Compose

## Dependencies

- Doctrine ORM/DBAL
- Symfony Console/Framework/Runtime
- Monolog Bundle
- BCMath Extension

## Installation

### Prerequisites
- Docker and Docker Compose

### Setup

1. Clone the repository
2. Run `make build` to build and start containers
3. Profit

## Usage

1. Enter a gross annual salary in the input field
2. Click "Calc" to view the calculation results
3. Results show gross/net salaries and tax breakdown

## Tax Calculation

Example for £40,000:
- Tax Band A (0-5000, 0%): £0
- Tax Band B (5000-20000, 20%): £3,000
- Tax Band C (20000+, 40%): £8,000
- Total tax: £11,000

## Docker Commands

- make build    # Build and start containers
- make up       # Start containers
- make down     # Stop containers
- make restart  # Restart containers
- make logs     # View logs
- make shell    # Open shell in PHP container

## Design Approach

The application follows object-oriented principles and design patterns including:
- Repository pattern for data access
- Service layer for business logic
- Dependency injection for loose coupling