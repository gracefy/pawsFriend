-- create database
CREATE DATABASE IF NOT EXISTS pawsfriend;


-- create user
CREATE USER pawsAdmin@localhost IDENTIFIED BY 'pawsfriend';
GRANT ALL PRIVILEGES ON pawsfriend.* TO pawsAdmin@localhost;


-- create tables
USE pawsfriend;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`user_id`)
);

CREATE TABLE IF NOT EXISTS `addresses` (
  `address_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` MEDIUMINT UNSIGNED NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `province` CHAR(2) NOT NULL,
  `city` VARCHAR(50) NOT NULL,
  `street` VARCHAR(200) NOT NULL,
  `street_number` VARCHAR(20) NOT NULL,
  `apartment` VARCHAR(100),
  `unit_number` VARCHAR(20),
  PRIMARY KEY (`address_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(20) NOT NULL,
  `description` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`category_id`)
);

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` MEDIUMINT UNSIGNED NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `description` text NOT NULL,
  `unit_price` decimal(5,2) NOT NULL,
  `qty_instock` int NOT NULL,
  `image_url` VARCHAR(255),
  PRIMARY KEY (`product_id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`category_id`)
);

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` MEDIUMINT UNSIGNED NOT NULL,
  `order_date` datetime NOT NULL,
  PRIMARY KEY (`order_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);

CREATE TABLE IF NOT EXISTS `order_items` (
  `item_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` MEDIUMINT UNSIGNED NOT NULL,
  `product_id` MEDIUMINT UNSIGNED NOT NULL,
  `quantiity` int NOT NULL,
  `discount` decimal(3,2),
  PRIMARY KEY (`item_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`product_id`)
);

CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` MEDIUMINT UNSIGNED NOT NULL,
  `cart_date` datetime NOT NULL,
  PRIMARY KEY (`cart_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);

CREATE TABLE IF NOT EXISTS `cart_items` (
  `cart_item_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cart_id` MEDIUMINT UNSIGNED NOT NULL,
  `product_id` MEDIUMINT UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`cart_item_id`),
  FOREIGN KEY (`cart_id`) REFERENCES `cart`(`cart_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`product_id`)
);


CREATE TABLE IF NOT EXISTS `admin_users` (
  `user_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`user_id`)
);


CREATE TABLE IF NOT EXISTS `pet_category` (
  `category_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(20) NOT NULL,
  `description` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`category_id`)
);

CREATE TABLE IF NOT EXISTS `pets` (
  `pet_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` MEDIUMINT UNSIGNED NOT NULL,
  `pet_name` VARCHAR(255) NOT NULL,
  `description` text NOT NULL,
  `unit_price` decimal(5,2) NOT NULL,
  `image_url` VARCHAR(255),
  PRIMARY KEY (`pet_id`),
  FOREIGN KEY (`category_id`) REFERENCES `pet_category`(`category_id`)
);


CREATE TABLE IF NOT EXISTS `blogs` (
  `blog_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(255) NOT NULL,
  `create_date` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `image_url` VARCHAR(100),
  PRIMARY KEY (`blog_id`)
);


CREATE TABLE IF NOT EXISTS `contacts` (
  `contact_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `create_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contact_id`)
);



--insert data
--users
INSERT INTO users (name, email, password)
VALUES
("Thu Oct","thu@test.com","123456"),
("Fri Novem","fri@test.com","123456"),
("Satur Decem","satur@test.com","123456"),
("Smart","smart@test.com","123456"),
("Brave","brave@test.com","123456"),
("Dog Honey","doghoney@test.com","123456"),
("Cat Cute","catcat@test.com","123456"),
("Kind","kind@test.com","123456"),
("Warm Winter","warm333@test.com","123456"),
("Sunny Spring","sspring@test.com","123456");

INSERT INTO users (name, email, password)
VALUES
("Coco","coco@test.com",SHA2("123456", 256)),
("Fyer","fyer@test.com",SHA2("111111", 256)),
("Jame","jame@test.com",SHA2("111112", 256)),
("Window","window@test.com",SHA2("111113", 256)),
("Honey","honey@test.com",SHA2("111114", 256)),
("Cute","cute@test.com",SHA2("111115", 256)),
("Beauty","beauty@test.com",SHA2("111116", 256)),
("Warm","warm22@test.com",SHA2("111117", 256)),
("Sunny","sunny@test.com",SHA2("111118", 256)),
("Lily","lily@test.com",SHA2("111119", 256)),
("Lucy","lucy@test.com",SHA2("111111", 256)),
("James","james@test.com",SHA2("111112", 256)),
("Windows","windows@test.com",SHA2("111113", 256)),
("Honeyyy","honeyyy@test.com",SHA2("111114", 256));

--address

INSERT INTO addresses (user_id, phone, street_number, street, city, province)
VALUES
("1","111-111-1111","9991","Happy Street","Waterloo","ON"),
("2","111-111-1112","9992","Happy Street","Kitchener","ON"),
("3","111-111-1113","9993","Happy Street","Waterloo","ON"),
("4","111-111-1114","9994","Happy Street","Kitchener","ON"),
("5","111-111-1115","9995","Happy Street","Waterloo","ON"),
("6","111-111-1116","9996","Happy Street","Kitchener","ON"),


INSERT INTO addresses (user_id, phone, street_number, street, city, province, apartment, unit_number)
VALUES
("7","111-111-1117","9997","Happy Street","Waterloo","ON","Condo","707"),
("8","111-111-1118","9998","Happy Street","Kitchener","ON","Condo","505"),
("9","111-111-1119","9999","Happy Street","Waterloo","ON","Condo","202"),
("10","111-111-1110","9990","Happy Street","Kitchener","ON","Condo","101");


--admin_users
INSERT INTO admin_users (username, email, password)
VALUES
("Grace","grace@test.com","123456"),
("PawsFriend","paws@test.com","123456");


--product category
INSERT INTO categories (category_name, description)
VALUES
("food","pets food, treats"),
("accessory","pets toys,cloths");

--products
INSERT INTO products (category_id, product_name, description,unit_price,qty_instock,image_url)
VALUES
('2','Dogs clothes',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'19.99','100','uploads/product-deal.jpeg'),
('2','Dogs toys',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'39.99','100','uploads/product-deal.jpeg'),
('2','Dogs clothes',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'29.99','66','uploads/product-deal.jpeg'),
('2','Dogs toys',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'109.99','50','uploads/product-deal.jpeg')


--carts
INSERT INTO carts (user_id,cart_date)
VALUES
('1','2023-08-19'),
('2','2023-02-19'),
('7','2023-10-19'),
('10','2023-06-01'),
('11','2023-08-01'),
('20','2023-11-01'),
('23','2023-11-07'),
('28','2023-09-07'),
('24','2023-10-18'),
('33','2023-10-18'),
('35','2023-08-18'),
('40','2023-06-18'),
('44','2023-05-18'),
('62','2023-04-18')


-- cart items
INSERT INTO cart_items (cart_id, product_id, quantity)
VALUES
('1','1','1'),
('1','2','1'),
('1','3','2'),
('2','1','2'),
('2','6','1'),
('3','2','2'),
('3','1','2'),
('4','5','1'),
('4','1','10'),
('5','3','5'),
('10','1','2'),
('8','12','3'),
('6','4','1')

--orders
INSERT INTO orders (user_id,order_date)
VALUES
('1','2023-08-19'),
('2','2023-02-19'),
('7','2023-10-19'),
('10','2023-06-01'),
('11','2023-08-01'),
('20','2023-11-01'),
('23','2023-11-07'),
('28','2023-09-07'),
('24','2023-10-18'),
('33','2023-10-18'),
('35','2023-08-18'),
('40','2023-06-18'),
('44','2023-05-18'),
('62','2023-04-18')

--order items
INSERT INTO order_items (order_id, product_id, quantity, discount)
VALUES
('1','1','1','1'),
('1','2','1','1'),
('1','3','2','1'),
('2','1','2','0.8'),
('2','6','1','1'),
('3','2','2','1'),
('3','1','2','0.9'),
('4','5','1','1'),
('4','1','10','1'),
('5','3','5','1'),
('10','1','2','0.6'),
('8','12','3','1'),
('6','4','1','0.8')


--pet category
INSERT INTO pet_category (category_name, description)
VALUES
("cat","kitty, cats"),
("dog","puppy, dogs"),
("other pet","small animals except cats and dogs")


-- pets
INSERT INTO pets (category_id, pet_name, description, unit_price, image_url)
VALUES
('1','Cat One',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'280.00','uploads/adopt-cat.jpeg'),
('1','Cat Two',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'300.00','uploads/adopt-cat.jpeg'),
('1','Cat Three',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'220.00','uploads/adopt-cat.jpeg'),
('2','Dog One',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'250.00','uploads/adopt-dog.jpeg'),
('2','Dog Two',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'220.00','uploads/adopt-dog.jpeg'),
('2','Dog Three',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'250.00','uploads/adopt-dog.jpeg'),
('3','Rabbit One',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'80.00','uploads/adopt-rabbit.jpeg'),
('3','Rabbit Two',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'90.00','uploads/adopt-rabbit.jpeg'),
('3','Rabbit Three',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'100.00','uploads/adopt-rabbit.jpeg'),
('1','Cat Four',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'280.00','uploads/adopt-cat.jpeg'),
('1','Cat Five',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'260.00','uploads/adopt-cat.jpeg'),
('1','Cat Six',
'Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.',
'270.00','uploads/adopt-cat.jpeg')



-- blogs
INSERT INTO blogs (title, author, create_date, content, image_url)
VALUES
("10 Things To Do Before You Adopt a Pet","Emma Smiths","2023-10-20","<p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P> <p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P>","aflive.qiniu.huangmeimi.com/uPic/blog-card.jpeg"),
("Tips for Choosing the Perfect Pet","Sarah Johnson","2023-09-15","<p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P> <p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P>","aflive.qiniu.huangmeimi.com/uPic/blog-card.jpeg"),
("The Joys of Pet Ownership","Michael Williams","2023-08-25","<p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P> <p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P>","aflive.qiniu.huangmeimi.com/uPic/blog-card.jpeg"),
("A Day in the Life of a Pet Parent","Jessica Miller","2023-07-12","<p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P> <p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P>","aflive.qiniu.huangmeimi.com/uPic/blog-card.jpeg"),
("Choosing the Right Pet for Your Lifestyle","Daniel Brown","2023-06-02","<p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P> <p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P>","aflive.qiniu.huangmeimi.com/uPic/blog-card.jpeg"),
("The Benefits of Adopting a Rescue Pet","Emily Davis","2023-05-18","<p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P> <p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P>","aflive.qiniu.huangmeimi.com/uPic/blog-card.jpeg"),
("Creating a Pet-Friendly Home","Christopher Taylor","2023-04-09","<p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P> <p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P>","aflive.qiniu.huangmeimi.com/uPic/blog-card.jpeg"),
("Pet Health: A Guide to Regular Vet Visits","Matthew Lee","2023-02-14","<p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P> <p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P>","aflive.qiniu.huangmeimi.com/uPic/blog-card.jpeg"),
("The Art of Pet Training","Sophia Clark","2023-01-05","<p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P> <p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P>","aflive.qiniu.huangmeimi.com/uPic/blog-card.jpeg"),
("Caring for Senior Pets","William Baker","2022-12-18","<p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P> <p>Phasellus a leo id arcu bibendum auctor. Pellentesque sollicitudin dignissim porta. Cras vel risus commodo ligula rhoncus posuere vel quis quam. Proin at elit dui. Fusce fringilla auctor gravida. Pellentesque tincidunt fermentum tempor. Cras vitae gravida mauris. Integer dictum urna sit amet interdum fringilla. Ut ullamcorper magna dolor. Proin id lorem nunc. Vestibulum in elit luctus, blandit risus vel, tristique lorem. Duis et faucibus lorem, in aliquet metus.</P>","aflive.qiniu.huangmeimi.com/uPic/blog-card.jpeg")



-- contacts
INSERT INTO `contacts` (`name`, `phone`, `email`, `content`, `create_date`)
VALUES 
('Alice Smith', '555-1234', 'alice@example.com', 'Content 1', '2023-11-15 08:45:00'),
('Bob Johnson', '555-5678', 'bob@example.com', 'Content 2', '2023-11-15 10:30:00'),
('Charlie Brown', '555-9876', 'charlie@example.com', 'Content 3', '2023-11-16 14:20:00'),
('David Wilson', '555-4321', 'david@example.com', 'Content 4', '2023-11-17 09:15:00'),
('Eva Davis', '555-8765', 'eva@example.com', 'Content 5', '2023-11-18 11:45:00'),
('Frank Miller', '555-5678', 'frank@example.com', 'Content 6', '2023-11-19 15:30:00'),
('Grace Lee', '555-3456', 'grace@example.com', 'Content 7', '2023-11-20 12:00:00'),
('Henry Young', '555-7890', 'henry@example.com', 'Content 8', '2023-11-21 14:45:00'),
('Ivy Taylor', '555-2345', 'ivy@example.com', 'Content 9', '2023-11-22 09:30:00'),
('Jack Brown', '555-6789', 'jack@example.com', 'Content 10', '2023-11-23 16:00:00')

-- update data
UPDATE products
SET image_url = 'aflive.qiniu.huangmeimi.com/uPic/product-food.jpeg'
WHERE image_url = 'uploads/product-food.jpeg';


-- add field
ALTER TABLE products
ADD COLUMN create_date DATETIME DEFAULT CURRENT_TIMESTAMP;


-- add update time field
ALTER TABLE cart_items
ADD COLUMN update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;