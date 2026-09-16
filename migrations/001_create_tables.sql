CREATE DATABASE IF NOT EXISTS cinema_booking
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cinema_booking;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'admin',
    name VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS halls (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    rows_count INT UNSIGNED NOT NULL,
    seats_per_row_count INT UNSIGNED NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    INDEX idx_halls_is_active (is_active)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS seats (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hall_id BIGINT UNSIGNED NOT NULL,
    row_num INT UNSIGNED NOT NULL,
    seat_number INT UNSIGNED NOT NULL,
    type VARCHAR(20) NOT NULL DEFAULT 'standard',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_seats_hall_row_seat (
        hall_id,
        row_num,
        seat_number
    ),

    INDEX idx_seats_hall_id (hall_id),

    CONSTRAINT fk_seats_hall
        FOREIGN KEY (hall_id)
        REFERENCES halls (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS movies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    duration_minutes INT UNSIGNED NOT NULL,
    poster_url VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    INDEX idx_movies_is_active (is_active)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS screenings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hall_id BIGINT UNSIGNED NOT NULL,
    movie_id BIGINT UNSIGNED NOT NULL,
    start_at DATETIME NOT NULL,
    end_at DATETIME NOT NULL,
    price_standard DECIMAL(10, 2) NOT NULL,
    price_vip DECIMAL(10, 2) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,

    INDEX idx_screenings_hall_start (hall_id, start_at),
    INDEX idx_screenings_movie_start (movie_id, start_at),
    INDEX idx_screenings_is_active (is_active),

    CONSTRAINT fk_screenings_hall
        FOREIGN KEY (hall_id)
        REFERENCES halls (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_screenings_movie
        FOREIGN KEY (movie_id)
        REFERENCES movies (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    screening_id BIGINT UNSIGNED NOT NULL,
    seat_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    booking_code VARCHAR(64) NOT NULL UNIQUE,
    qr_code_path VARCHAR(255) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'reserved',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,

    UNIQUE KEY uq_tickets_screening_seat (
        screening_id,
        seat_id
    ),

    INDEX idx_tickets_screening_id (screening_id),
    INDEX idx_tickets_seat_id (seat_id),
    INDEX idx_tickets_user_id (user_id),

    CONSTRAINT fk_tickets_screening
        FOREIGN KEY (screening_id)
        REFERENCES screenings (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_tickets_seat
        FOREIGN KEY (seat_id)
REFERENCES seats (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_tickets_user
        FOREIGN KEY (user_id)
        REFERENCES users (id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
