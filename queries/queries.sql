
#Creates table users

CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `date_of_registration` DATE NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;

#Creates table cities

CREATE TABLE `cities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`));

#Creates table landmarks

CREATE TABLE `landmarks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `city_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `review` TEXT NULL,
  `status` BINARY NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_idx` (`user_id` ASC),
  CONSTRAINT `user`
    FOREIGN KEY (`user_id`)
    REFERENCES `landmark`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

#Altering table landmarks - adding foreign key

ALTER TABLE `landmarks` 
ADD INDEX `city_idx` (`city_id` ASC);
ALTER TABLE `landmark`.`landmarks` 
ADD CONSTRAINT `city`
  FOREIGN KEY (`city_id`)
  REFERENCES `landmark`.`cities` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

#Creates table landmark_ratings

CREATE TABLE `landmark_ratings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `rating` INT NOT NULL,
  `place_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_idx` (`user_id` ASC),
  INDEX `landmark_idx` (`place_id` ASC),
  CONSTRAINT `users`
    FOREIGN KEY (`user_id`)
    REFERENCES `landmark`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `landmark`
    FOREIGN KEY (`place_id`)
    REFERENCES `landmark`.`landmarks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

#Creates table landmark_images

CREATE TABLE `landmark_images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `image` VARCHAR(255) NOT NULL,
  `place_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `images_idx` (`place_id` ASC),
  CONSTRAINT `images`
    FOREIGN KEY (`place_id`)
    REFERENCES `landmark`.`landmarks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `landmark_images` 
ADD COLUMN `type` ENUM('small','medium','big') NOT NULL AFTER `place_id`;

ALTER TABLE `landmark_images` 
CHANGE COLUMN `type` `type` ENUM('small','medium','big','deleted') NOT NULL ;

#Creates table landmark_hotels

CREATE TABLE `landmark_hotels` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `category` INT NOT NULL,
  `review` TEXT NULL,
  `city_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `city_idx` (`city_id` ASC),
  CONSTRAINT `cities`
    FOREIGN KEY (`city_id`)
    REFERENCES `landmark`.`cities` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

#Creates table landmark_restaurants

CREATE TABLE `landmark_restaurants` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `city_id` INT NOT NULL,
  `type` ENUM('BAR','RESTAURANT','DISCO') NOT NULL,
  `review` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `bar_city_idx` (`city_id` ASC),
  CONSTRAINT `bar_city`
    FOREIGN KEY (`city_id`)
    REFERENCES `landmark`.`cities` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);