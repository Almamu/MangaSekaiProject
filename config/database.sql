
-- ---------------------------------------------------------------------
-- chapter_tracker
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `chapter_tracker`;

CREATE TABLE `chapter_tracker`
(
    `id_chapter` INTEGER NOT NULL,
    `id_user` INTEGER NOT NULL,
    PRIMARY KEY (`id_chapter`,`id_user`)
);

-- ---------------------------------------------------------------------
-- chapters
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `chapters`;

CREATE TABLE `chapters`
(
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `id_series` INTEGER NOT NULL,
    `pages_count` INTEGER NOT NULL,
    `number` INTEGER NOT NULL
);

-- ---------------------------------------------------------------------
-- series
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `series`;

CREATE TABLE `series`
(
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `name` text NOT NULL,
    `chapter_count` INTEGER NOT NULL,
    `pages_count` INTEGER NOT NULL,
    `description` TEXT NOT NULL,
    `synced` BOOLEAN DEFAULT 0 NOT NULL
);

-- ---------------------------------------------------------------------
-- series_tracker
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `series_tracker`;

CREATE TABLE `series_tracker`
(
    `id_user` INTEGER NOT NULL,
    `id_series` INTEGER NOT NULL,
    PRIMARY KEY (`id_user`,`id_series`)
);

-- ---------------------------------------------------------------------
-- users
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users`
(
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `username` TEXT NOT NULL,
    `password` CHAR(64) NOT NULL
);
