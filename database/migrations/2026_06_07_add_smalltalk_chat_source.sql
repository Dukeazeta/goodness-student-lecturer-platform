USE goodness_platform;

ALTER TABLE chat_logs
    MODIFY source ENUM('faq', 'material', 'smalltalk', 'fallback', 'ai', 'validation') NOT NULL DEFAULT 'faq';
