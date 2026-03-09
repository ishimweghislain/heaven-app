-- Create database
CREATE DATABASE IF NOT EXISTS success_microfinance
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE success_microfinance;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role VARCHAR(20) NOT NULL DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- News table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255) NOT NULL,
    title_am VARCHAR(255) NOT NULL,
    content_en TEXT NOT NULL,
    content_am TEXT NOT NULL,
    author VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    published_date DATE NOT NULL,
    status TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(255) NOT NULL,
    name_am VARCHAR(255) NOT NULL,
    description_en TEXT NOT NULL,
    description_am TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    requirements_en TEXT NOT NULL,
    requirements_am TEXT NOT NULL,
    image VARCHAR(255),
    status TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Content table
CREATE TABLE IF NOT EXISTS content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section VARCHAR(50) NOT NULL,
    `key` VARCHAR(100) NOT NULL,
    value_en TEXT NOT NULL,
    value_am TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY section_key (section, `key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Partners table
CREATE TABLE IF NOT EXISTS partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo VARCHAR(255) NOT NULL,
    url VARCHAR(255),
    status TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Media table
CREATE TABLE IF NOT EXISTS media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    size INT NOT NULL,
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user
INSERT INTO users (username, password, email, role) VALUES 
('admin', '$2y$10$8zUlxQxkK2h.LAvkXu.GIeYwGUYfYBXRHQfEAJqOVTKNNvgwQkYl2', 'admin@successmfi.com', 'admin');

-- Insert sample content
INSERT INTO content (section, `key`, value_en, value_am) VALUES
('hero', 'title', 'SUCCESS MICROFINANCE INSTITUTION S.C.', 'የስኬት ማይክሮፋይናንስ ተቋም'),
('hero', 'tagline', 'Becoming best, inclusive, sustainable economic and social vehicle in poverty alleviation efforts in Ethiopia and East Africa by 2040.', 'በ2040...'),
('about', 'vision', 'Becoming best, inclusive, sustainable economic and social vehicle...', 'በ2040...'),
('about', 'mission', 'Providing microfinance services tailored to client needs...', 'የረጅም ጊዜ...'),
('about', 'core_values', 'Responsibility, Staff Commitment, Client Understanding...', 'ኃላፊነት፣...');



-- Insert sample products

INSERT INTO products (name_en, name_am, description_en, description_am, category, requirements_en, requirements_am, image, status) VALUES
('Personal Loan', 'የግል ብድር', 'A loan product designed to meet individual financial needs.', 'የግል የገንዘብ ፍላጎቶችን ለማሟላት የተዘጋጀ የብድር ምርት።', 'loans', 'Renewed business license; Business registration certificate (business address verification); Taxpayer Identification Number (TIN) (business address verification); Infrastructure verification photo (2 photos); Business operation verification from guarantor (2 photos); Family information verification (2 photos); Loan application form; Proof of residential address; Renewed ID; 4x6 photo; Seal (1 for borrower, 1 for guarantor); Guarantor document; Guarantor''s proof of residential address; Guarantor''s renewed ID; Guarantor''s 4x6 photo; Seal.', 'የታደሰ ንግድ ፍቃድ; የንግድ ምዝገባ ምስክር ወረቀት (የንግድ አድራሻ ማስረጃ); የግብር ከፋይ መለያ ቁጥር (TIN) (የንግድ አድራሻ ማስረጃ); መሰረተ ልማት ማረጋገጫ ፎቶ (2 ፎቶ); ዋስ አብሮ የነበረው የንግድ ስራ ማስረጃ (2 ፎቶ); የቤተሰብ መረጃ ማረጋገጫ (2 ፎቶ); የብድር ማመልከቻ ቅጽ; የመኖሪያ አድራሻ ማስረጃ; የታደሰ መታወቂያ; 4×6 ፎቶ; ማህተም (ለተበዳሪ 1 ለዋስ 1); የዋስ ሰነድ; ዋስ የመኖሪያ አድራሻ ማስረጃ; የዋስ የታደሰ መታወቂያ; የዋስ 4×6 ፎቶ; ማህተም', NULL, 1),
('Small Business Loan', 'የአነስተኛ ንግድ ብድር', 'A loan product tailored for small businesses to support their operations and growth.', 'አነስተኛ የንግድ ስራዎችን ለመደገፍ እና ለማሳደግ የተበጀ የብድር ምርት።', 'loans', 'Renewed business license; Business registration certificate (business address verification); Taxpayer Identification Number (TIN) (business address verification); Infrastructure verification photo (2 photos); Business operation verification from guarantor (2 photos); Family information verification (2 photos); Loan application form; Proof of residential address; Renewed ID; 4x6 photo; Seal (1 for borrower, 1 for guarantor); Guarantor document; Guarantor''s proof of residential address; Guarantor''s renewed ID; Guarantor''s 4x6 photo; Seal.', 'የታደሰ ንግድ ፍቃድ; የንግድ ምዝገባ ምስክር ወረቀት (የንግድ አድራሻ ማስረጃ); የግብር ከፋይ መለያ ቁጥር (TIN) (የንግድ አድራሻ ማስረጃ); መሰረተ ልማት ማረጋገጫ ፎቶ (2 ፎቶ); ዋስ አብሮ የነበረው የንግድ ስራ ማስረጃ (2 ፎቶ); የቤተሰብ መረጃ ማረጋገጫ (2 ፎቶ); የብድር ማመልከቻ ቅጽ; የመኖሪያ አድራሻ ማስረጃ; የታደሰ መታወቂያ; 4×6 ፎቶ; ማህተም (ለተበዳሪ 1 ለዋስ 1); የዋስ ሰነድ; ዋስ የመኖሪያ አድራሻ ማስረጃ; የዋስ የታደሰ መታወቂያ; የዋስ 4×6 ፎቶ; ማህተም', NULL, 1),
('Agricultural Loan', 'የግብርና ብድር', 'A loan product designed to support agricultural activities and farmers.', 'የግብርና እንቅስቃሴዎችን እና ገበሬዎችን ለመደገፍ የተነደፈ የብድር ምርት።', 'loans', 'Renewed business license; Business registration certificate (business address verification); Taxpayer Identification Number (TIN) (business address verification); Infrastructure verification photo (2 photos); Business operation verification from guarantor (2 photos); Family information verification (2 photos); Loan application form; Proof of residential address; Renewed ID; 4x6 photo; Seal (1 for borrower, 1 for guarantor); Guarantor document; Guarantor''s proof of residential address; Guarantor''s renewed ID; Guarantor''s 4x6 photo; Seal.', 'የታደሰ ንግድ ፍቃድ; የንግድ ምዝገባ ምስክር ወረቀት (የንግድ አድራሻ ማስረጃ); የግብር ከፋይ መለያ ቁጥር (TIN) (የንግድ አድራሻ ማስረጃ); መሰረተ ልማት ማረጋገጫ ፎቶ (2 ፎቶ); ዋስ አብሮ የነበረው የንግድ ስራ ማስረጃ (2 ፎቶ); የቤተሰብ መረጃ ማረጋገጫ (2 ፎቶ); የብድር ማመልከቻ ቅጽ; የመኖሪያ አድራሻ ማስረጃ; የታደሰ መታወቂያ; 4×6 ፎቶ; ማህተም (ለተበዳሪ 1 ለዋስ 1); የዋስ ሰነድ; ዋስ የመኖሪያ አድራሻ ማስረጃ; የዋስ የታደሰ መታወቂያ; የዋስ 4×6 ፎቶ; ማህተም', NULL, 1),
('Housing Loan', 'የቤቶች ብድር', 'A loan product for individuals seeking to purchase or build a home.', 'ቤት ለመግዛት ወይም ለመገንባት ለሚፈልጉ ግለሰቦች የተዘጋጀ የብድር ምርት።', 'loans', 'Renewed business license; Business registration certificate (business address verification); Taxpayer Identification Number (TIN) (business address verification); Infrastructure verification photo (2 photos); Business operation verification from guarantor (2 photos); Family information verification (2 photos); Loan application form; Proof of residential address; Renewed ID; 4x6 photo; Seal (1 for borrower, 1 for guarantor); Guarantor document; Guarantor''s proof of residential address; Guarantor''s renewed ID; Guarantor''s 4x6 photo; Seal.', 'የታደሰ ንግድ ፍቃድ; የንግድ ምዝገባ ምስክር ወረቀት (የንግድ አድራሻ ማስረጃ); የግብር ከፋይ መለያ ቁጥር (TIN) (የንግድ አድራሻ ማስረጃ); መሰረተ ልማት ማረጋገጫ ፎቶ (2 ፎቶ); ዋስ አብሮ የነበረው የንግድ ስራ ማስረጃ (2 ፎቶ); የቤተሰብ መረጃ ማረጋገጫ (2 ፎቶ); የብድር ማመልከቻ ቅጽ; የመኖሪያ አድራሻ ማስረጃ; የታደሰ መታወቂያ; 4×6 ፎቶ; ማህተም (ለተበዳሪ 1 ለዋስ 1); የዋስ ሰነድ; ዋስ የመኖሪያ አድራሻ ማስረጃ; የዋስ የታደሰ መታወቂያ; የዋስ 4×6 ፎቶ; ማህተም', NULL, 1),
('Group Loan', 'የጋራ ብድር', 'A loan product offered to a group of individuals, often for collective economic activities.', 'በጋራ ለሚሰሩ የኢኮኖሚ እንቅስቃሴዎች ለቡድን የሚሰጥ የብድር ምርት።', 'loans', 'Renewed business license; Business registration certificate (business address verification); Taxpayer Identification Number (TIN) (business address verification); Infrastructure verification photo (2 photos); Business operation verification from guarantor (2 photos); Family information verification (2 photos); Loan application form; Proof of residential address; Renewed ID; 4x6 photo; Seal (1 for borrower, 1 for guarantor); Guarantor document; Guarantor''s proof of residential address; Guarantor''s renewed ID; Guarantor''s 4x6 photo; Seal.', 'የታደሰ ንግድ ፍቃድ; የንግድ ምዝገባ ምስክር ወረቀት (የንግድ አድራሻ ማስረጃ); የግብር ከፋይ መለያ ቁጥር (TIN) (የንግድ አድራሻ ማስረጃ); መሰረተ ልማት ማረጋገጫ ፎቶ (2 ፎቶ); ዋስ አብሮ የነበረው የንግድ ስራ ማስረጃ (2 ፎቶ); የቤተሰብ መረጃ ማረጋገጫ (2 ፎቶ); የብድር ማመልከቻ ቅጽ; የመኖሪያ አድራሻ ማስረጃ; የታደሰ መታወቂያ; 4×6 ፎቶ; ማህተም (ለተበዳሪ 1 ለዋስ 1); የዋስ ሰነድ; ዋስ የመኖሪያ አድራሻ ማስረጃ; የዋስ የታደሰ መታወቂያ; የዋስ 4×6 ፎቶ; ማህተም', NULL, 1),
('Service Loan', 'የአግልግሎት ብድር', 'A loan product to finance various service-related needs.', 'የተለያዩ ከአገልግሎት ጋር የተያያዙ ፍላጎቶችን ለመደገፍ የተዘጋጀ የብድር ምርት።', 'loans', 'Renewed business license; Business registration certificate (business address verification); Taxpayer Identification Number (TIN) (business address verification); Infrastructure verification photo (2 photos); Business operation verification from guarantor (2 photos); Family information verification (2 photos); Loan application form; Proof of residential address; Renewed ID; 4x6 photo; Seal (1 for borrower, 1 for guarantor); Guarantor document; Guarantor''s proof of residential address; Guarantor''s renewed ID; Guarantor''s 4x6 photo; Seal.', 'የታደሰ ንግድ ፍቃድ; የንግድ ምዝገባ ምስክር ወረቀት (የንግድ አድራሻ ማስረጃ); የግብር ከፋይ መለያ ቁጥር (TIN) (የንግድ አድራሻ ማስረጃ); መሰረተ ልማት ማረጋገጫ ፎቶ (2 ፎቶ); ዋስ አብሮ የነበረው የንግድ ስራ ማስረጃ (2 ፎቶ); የቤተሰብ መረጃ ማረጋገጫ (2 ፎቶ); የብድር ማመልከቻ ቅጽ; የመኖሪያ አድራሻ ማስረጃ; የታደሰ መታወቂያ; 4×6 ፎቶ; ማህተም (ለተበዳሪ 1 ለዋስ 1); የዋስ ሰነድ; ዋስ የመኖሪያ አድራሻ ማስረጃ; የዋስ የታደሰ መታወቂያ; የዋስ 4×6 ፎቶ; ማህተም', NULL, 1),
('Land Purchase Loan', 'የመሬት ግዥ ብድር', 'A loan product specifically for the purchase of land.', 'መሬት ለመግዛት የተለየ የብድር ምርት።', 'loans', 'Renewed business license; Business registration certificate (business address verification); Taxpayer Identification Number (TIN) (business address verification); Infrastructure verification photo (2 photos); Business operation verification from guarantor (2 photos); Family information verification (2 photos); Loan application form; Proof of residential address; Renewed ID; 4x6 photo; Seal (1 for borrower, 1 for guarantor); Guarantor document; Guarantor''s proof of residential address; Guarantor''s renewed ID; Guarantor''s 4x6 photo; Seal.', 'የታደሰ ንግድ ፍቃድ; የንግድ ምዝገባ ምስክር ወረቀት (የንግድ አድራሻ ማስረጃ); የግብር ከፋይ መለያ ቁጥር (TIN) (የንግድ አድራሻ ማስረጃ); መሰረተ ልማት ማረጋገጫ ፎቶ (2 ፎቶ); ዋስ አብሮ የነበረው የንግድ ስራ ማስረጃ (2 ፎቶ); የቤተሰብ መረጃ ማረጋገጫ (2 ፎቶ); የብድር ማመልከቻ ቅጽ; የመኖሪያ አድራሻ ማስረጃ; የታደሰ መታወቂያ; 4×6 ፎቶ; ማህተም (ለተበዳሪ 1 ለዋስ 1); የዋስ ሰነድ; ዋስ የመኖሪያ አድራሻ ማስረጃ; የዋስ የታደሰ መታወቂያ; የዋስ 4×6 ፎቶ; ማህተም', NULL, 1),
('Children''s Savings', 'የልጆች ቁጠባ', 'A savings product designed for children, encouraging early saving habits. Interest rate: 9.5-12%', 'ለህፃናት የተዘጋጀ የቁጠባ ምርት, ገና በልጅነት ጊዜ የመቆጠብ ልምድን የሚያበረታታ። የወለድ መጠን: 9.5-12%', 'savings', 'N/A', 'የማይመለከት', NULL, 1),
('Pension Savings', 'የጡረታ ቁጠባ', 'A savings product for retirement planning. Interest rate: 9%', 'ለጡረታ እቅድ የተዘጋጀ የቁጠባ ምርት። የወለድ መጠን: 9%', 'savings', 'N/A', 'የማይመለከት', NULL, 1),
('Fixed Deposit Savings', 'የቋሚ ተቀማጭ ቁጠባ', 'A savings product with a fixed interest rate for a specific period. Interest rate: 9%', 'ለተወሰነ ጊዜ ቋሚ የወለድ መጠን ያለው የቁጠባ ምርት። የወለድ መጠን: 9%', 'savings', 'N/A', 'የማይመለከት', NULL, 1),
('Regular Savings', 'የተለመደ ቁጠባ', 'A standard savings account for regular deposits. Interest rate: 9%', 'ለመደበኛ ተቀማጭ ገንዘብ የሚሆን መደበኛ የቁጠባ ሂሳብ። የወለድ መጠን: 9%', 'savings', 'N/A', 'የማይመለከት', NULL, 1),
('Group Savings', 'የቡድን ቁጠባ', 'A savings product for groups or associations. Interest rate: 9%', 'ለቡድኖች ወይም ማህበራት የተዘጋጀ የቁጠባ ምርት። የወለድ መጠን: 9%', 'savings', 'N/A', 'የማይመለከት', NULL, 1),
('Voluntary Savings', 'የፍቃደኛ ቁጠባ', 'A flexible savings product where individuals can deposit at their convenience. Interest rate: 9%', 'ግለሰቦች በሚመቻቸው ጊዜ ገንዘብ ማስገባት የሚችሉበት ተለዋዋጭ የቁጠባ ምርት። የወለድ መጠን: 9%', 'savings', 'N/A', 'የማይመለከት', NULL, 1),
('Interest-Free Deposit Savings', 'ወለድ አልባ ተቀማጭ ቁጠባ', 'A savings product that does not accrue interest. Interest rate: 0%', 'ወለድ የማይከፈልበት የቁጠባ ምርት። የወለድ መጠን: 0%', 'savings', 'N/A', 'የማይመለከት', NULL, 1);

-- Insert sample partners
INSERT INTO partners (name, logo, url, status) VALUES
('Awash Bank', 'partner1.png', 'https://www.awashbank.com', 1),
('Commercial Bank of Ethiopia', 'partner2.png', 'https://www.combanketh.et', 1),
('GLOBAL Bank', 'partner3.png', 'https://www.globalbank.com', 1),
('Bank of Abyssinia', 'partner4.png', 'https://www.bankofabyssinia.com', 1);

-- Insert sample news (fixed the last broken line)
INSERT INTO news (title_en, title_am, content_en, content_am, author, image, published_date, status) VALUES
('New Branch Opening in Addis Ababa', 'በአዲስ አበባ አዲስ ቅርንጫፍ መክፈት', 'We are excited to announce the opening...', 'በአዲስ አበባ...', 'Admin Team', 'news1.jpg', '2025-05-15', 1),
('Financial Literacy Workshop Series', 'የፋይናንስ እውቀት የሥልጠና ትምህርቶች', 'We’ve launched a new workshop series...', 'አዲስ የሥልጠና...', 'Training Department', 'news2.jpg', '2025-05-18', 1);
