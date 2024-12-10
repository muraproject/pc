import os
import shutil

def create_project_structure():
    # Define the base directory
    base_dir = "aplikasi_timbangan"
    
    # Remove existing directory if exists
    if os.path.exists(base_dir):
        shutil.rmtree(base_dir)
    
    # Create main directories
    directories = [
        "",
        "api",
        "assets",
        "assets/css",
        "assets/js",
        "includes",
        "pages"
    ]
    
    for dir in directories:
        path = os.path.join(base_dir, dir)
        os.makedirs(path, exist_ok=True)
        
    # Create API files
    api_files = [
        "dashboard.php",
        "category_crud.php",
        "product_crud.php",
        "supplier_crud.php",
        "user_crud.php",
        "weighing_in.php",
        "weighing_out.php",
        "history_in.php",
        "history_out.php",
        "receipt_in.php",
        "receipt_out.php",
        "wages.php",
        "delete_weighing.php"
    ]
    
    for file in api_files:
        with open(os.path.join(base_dir, "api", file), 'w') as f:
            f.write("<?php\n// API endpoint for " + file)
            
    # Create asset files
    # CSS
    with open(os.path.join(base_dir, "assets/css/styles.css"), 'w') as f:
        f.write("/* Tailwind CSS styles */")
        
    # JavaScript files
    js_files = [
        "dashboard.js",
        "weighing-in.js",
        "weighing-out.js",
        "history-in.js",
        "history-out.js",
        "receipt-in.js",
        "receipt-out.js",
        "wages.js",
        "settings.js"
    ]
    
    for file in js_files:
        with open(os.path.join(base_dir, "assets/js", file), 'w') as f:
            f.write("// JavaScript for " + file)
            
    # Create include files
    include_files = [
        "config.php",
        "db.php",
        "functions.php",
        "navbar.php",
        "header.php"
    ]
    
    for file in include_files:
        with open(os.path.join(base_dir, "includes", file), 'w') as f:
            f.write("<?php\n// Include file for " + file)
            
    # Create page files
    page_files = [
        "dashboard.php",
        "weighing_in.php",
        "weighing_out.php",
        "history_in.php",
        "history_out.php",
        "receipt_in.php",
        "receipt_out.php",
        "wages.php",
        "settings.php"
    ]
    
    for file in page_files:
        with open(os.path.join(base_dir, "pages", file), 'w') as f:
            f.write("<?php\n// Page for " + file)
            
    # Create root files
    with open(os.path.join(base_dir, "index.php"), 'w') as f:
        f.write("<?php\n// Main index file")
        
    with open(os.path.join(base_dir, "login.php"), 'w') as f:
        f.write("<?php\n// Login page")

    # Create SQL file for database structure
    sql_content = """-- Create database
CREATE DATABASE IF NOT EXISTS aplikasi_timbangan;
USE aplikasi_timbangan;

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Suppliers table
CREATE TABLE suppliers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    wage_per_kg DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Weighing In table
CREATE TABLE weighing_in (
    id INT PRIMARY KEY AUTO_INCREMENT,
    receipt_id VARCHAR(50),
    supplier_id INT,
    category_id INT,
    product_id INT,
    weight DECIMAL(10,2) NOT NULL,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Weighing Out table
CREATE TABLE weighing_out (
    id INT PRIMARY KEY AUTO_INCREMENT,
    receipt_id VARCHAR(50),
    category_id INT,
    product_id INT,
    weight DECIMAL(10,2) NOT NULL,
    price DECIMAL(10,2) DEFAULT 0,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);"""

    with open(os.path.join(base_dir, "database.sql"), 'w') as f:
        f.write(sql_content)

    print(f"Project structure created in '{base_dir}' directory")
    print("Remember to:")
    print("1. Initialize Tailwind CSS")
    print("2. Configure your database connection in includes/config.php")
    print("3. Import database.sql to set up your database structure")

if __name__ == "__main__":
    create_project_structure()