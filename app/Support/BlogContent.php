<?php

namespace App\Support;

class BlogContent
{
    /**
     * Get all blog posts for the listing page.
     */
    public static function getAllPosts(): array
    {
        $slugs = require app_path('Support/tool_slugs.php');
        $tools = self::getToolList();
        $posts = [];

        foreach ($tools as $toolId => $tool) {
            if (!isset($slugs[$toolId])) {
                continue;
            }

            $slug = $slugs[$toolId];
            $category = $tool['category'];
            $post = [
                'slug' => $slug,
                'tool_id' => $toolId,
                'title' => self::getBlogTitle($toolId, $tool['name']),
                'excerpt' => $tool['desc'] . '. Learn how to use this free online tool to optimize your workflow with privacy-focused, browser-based processing.',
                'category' => self::getCategoryLabel($category),
                'category_slug' => $category,
                'read_time' => self::calculateReadTime($toolId),
                'date' => self::getPublishDate($toolId),
                'image' => self::getCategoryImage($category),
                'author' => 'Any2Convert Tech Team',
            ];
            $posts[] = $post;
        }

        // Add legacy blog posts to the list for backwards compatibility
        $posts[] = [
            'slug' => 'security-benefits',
            'tool_id' => null,
            'title' => 'Why Image to PDF is More Secure',
            'excerpt' => 'Learn the key PDF security benefits of converting photos to PDF documents, including password protection and formatting preservation.',
            'category' => 'Security',
            'category_slug' => 'pdf',
            'read_time' => '3 min read',
            'date' => 'May 12, 2026',
            'image' => '/images/blog/pdf_blog.png',
            'author' => 'Security Analyst',
        ];

        $posts[] = [
            'slug' => 'qr-guide',
            'tool_id' => null,
            'title' => 'Business QR Code Best Practices',
            'excerpt' => 'A comprehensive guide on creating clear, scan-friendly QR codes for links, menus, contacts, and marketing campaigns.',
            'category' => 'Business & Marketing',
            'category_slug' => 'business',
            'read_time' => '4 min read',
            'date' => 'June 05, 2026',
            'image' => '/images/blog/utility_blog.png',
            'author' => 'Marketing Team',
        ];

        return $posts;
    }

    /**
     * Get a specific blog post by slug.
     */
    public static function getPostBySlug(string $slug): ?array
    {
        $posts = self::getAllPosts();
        foreach ($posts as $post) {
            if ($post['slug'] === $slug) {
                // Generate content
                $post['content'] = self::generateContent($post);
                return $post;
            }
        }
        return null;
    }

    /**
     * Calculate static read time based on tool ID.
     */
    private static function calculateReadTime(string $toolId): string
    {
        $len = strlen($toolId);
        $min = 3 + ($len % 3);
        return $min . ' min read';
    }

    /**
     * Generate a deterministic publish date.
     */
    private static function getPublishDate(string $toolId): string
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $charSum = array_sum(str_split(bin2hex($toolId)));
        $day = 1 + ($charSum % 28);
        $month = $months[$charSum % 12];
        $year = 2025 + ($charSum % 2);
        return "$month " . str_pad($day, 2, '0', STR_PAD_LEFT) . ", $year";
    }

    /**
     * Get category image path based on category key.
     */
    public static function getCategoryImage(string $category): string
    {
        switch ($category) {
            case 'pdf':
                return '/images/blog/pdf_blog.png';
            case 'convert':
            case 'conversion':
                return '/images/blog/converter_blog.png';
            case 'gaming':
            case 'fun':
                return '/images/blog/gaming_blog.png';
            default:
                return '/images/blog/utility_blog.png';
        }
    }

    private static function getCategoryLabel(string $category): string
    {
        $labels = [
            'pdf' => 'PDF Guide',
            'convert' => 'Document Converter',
            'utility' => 'Utility Tool',
            'conversion' => 'Data Converter',
            'calculator' => 'Calculator Guide',
            'business' => 'Business Utility',
            'writing' => 'Writing Assistant',
            'developer' => 'Developer Utility',
            'gaming' => 'Gaming Tools',
            'fun' => 'Fun & Interactive',
        ];
        return $labels[$category] ?? 'Tutorial';
    }

    private static function getBlogTitle(string $toolId, string $name): string
    {
        $templates = [
            'How to Use %s: A Step-by-Step Browser Guide',
            'The Complete Guide to Free %s Online',
            'Why %s is Essential for Your Digital Workflow',
            'Optimize Your Files: %s Tutorial and Tips',
            'Mastering %s: Online File Processing Simplified',
        ];
        $sum = array_sum(str_split(bin2hex($toolId)));
        $tpl = $templates[$sum % count($templates)];
        return sprintf($tpl, $name);
    }

    /**
     * Get the full list of tools with category metadata.
     */
    private static function getToolList(): array
    {
        return [
            'img_to_pdf' => ['name' => 'Image to PDF', 'desc' => 'Convert JPG, PNG images to PDF documents', 'category' => 'pdf'],
            'split_pdf' => ['name' => 'Split PDF', 'desc' => 'Split one PDF into separate ranges', 'category' => 'pdf'],
            'pdf_to_img' => ['name' => 'PDF to Image', 'desc' => 'Extract images from PDF documents', 'category' => 'pdf'],
            'pdf_to_word' => ['name' => 'PDF to Word', 'desc' => 'Convert PDF to editable DOCX', 'category' => 'pdf'],
            'pdf_to_ppt' => ['name' => 'PDF to PPT', 'desc' => 'Convert PDF to PowerPoint', 'category' => 'pdf'],
            'pdf_to_excel' => ['name' => 'PDF to Excel', 'desc' => 'Extract tables from PDF files to XLSX format', 'category' => 'pdf'],
            'merge_pdf' => ['name' => 'Merge PDF', 'desc' => 'Merge and combine multiple PDF files', 'category' => 'pdf'],
            'organize_pdf' => ['name' => 'Organize PDF', 'desc' => 'Reorder pages into a new PDF', 'category' => 'pdf'],
            'remove_pages' => ['name' => 'Remove Pages', 'desc' => 'Delete unwanted pages quickly', 'category' => 'pdf'],
            'extract_pages' => ['name' => 'Extract Pages', 'desc' => 'Save selected pages as a new PDF', 'category' => 'pdf'],
            'rotate_pdf' => ['name' => 'Rotate PDF', 'desc' => 'Rotate every PDF page in one click', 'category' => 'pdf'],
            'compress_pdf' => ['name' => 'Compress PDF', 'desc' => 'Compress and reduce PDF file sizes', 'category' => 'pdf'],
            'optimize_pdf' => ['name' => 'Optimize PDF', 'desc' => 'Clean and optimize PDF structure', 'category' => 'pdf'],
            'repair_pdf' => ['name' => 'Repair PDF', 'desc' => 'Rebuild PDFs with minor issues', 'category' => 'pdf'],
            'ocr_pdf' => ['name' => 'OCR PDF', 'desc' => 'Extract text from scanned PDFs', 'category' => 'pdf'],
            'add_page_numbers' => ['name' => 'Add Page Numbers', 'desc' => 'Stamp page numbers on every page', 'category' => 'pdf'],
            'add_watermark' => ['name' => 'Add Watermark', 'desc' => 'Add text watermark to PDF pages', 'category' => 'pdf'],
            'protect_pdf' => ['name' => 'Protect PDF', 'desc' => 'Add password protection to PDF files', 'category' => 'pdf'],
            'unlock_pdf' => ['name' => 'Unlock PDF', 'desc' => 'Create an unlocked copy for viewing', 'category' => 'pdf'],
            'sign_pdf' => ['name' => 'Sign PDF', 'desc' => 'Place a signature image on a PDF', 'category' => 'pdf'],
            'crop_pdf' => ['name' => 'Crop PDF', 'desc' => 'Trim margins from PDF pages', 'category' => 'pdf'],
            'compare_pdf' => ['name' => 'Compare PDF', 'desc' => 'Compare text differences between PDFs', 'category' => 'pdf'],
            'ai_summarizer' => ['name' => 'AI Summarizer', 'desc' => 'Generate a quick PDF summary', 'category' => 'pdf'],
            'pdf_to_pdfa' => ['name' => 'PDF to PDF/A', 'desc' => 'Create an archival-style export', 'category' => 'pdf'],
            'edit_pdf' => ['name' => 'Edit PDF', 'desc' => 'Add text and images to a PDF', 'category' => 'pdf'],
            'redact_pdf' => ['name' => 'Redact PDF', 'desc' => 'Burn in keyword redactions', 'category' => 'pdf'],
            'translate_pdf' => ['name' => 'Translate PDF', 'desc' => 'Translate extracted PDF text', 'category' => 'pdf'],
            
            'word_to_pdf' => ['name' => 'Word to PDF', 'desc' => 'Convert DOC/DOCX documents to PDF', 'category' => 'convert'],
            'excel_to_pdf' => ['name' => 'Excel to PDF', 'desc' => 'Convert spreadsheets to PDF', 'category' => 'convert'],
            'ppt_to_pdf' => ['name' => 'PowerPoint to PDF', 'desc' => 'Convert PowerPoint slides to PDF format', 'category' => 'convert'],
            'html_to_pdf' => ['name' => 'HTML to PDF', 'desc' => 'Turn HTML into a PDF document', 'category' => 'convert'],
            'json_to_csv' => ['name' => 'JSON to CSV', 'desc' => 'Convert JSON to spreadsheet', 'category' => 'convert'],
            'csv_to_json' => ['name' => 'CSV to JSON', 'desc' => 'Convert CSV spreadsheet data to JSON format', 'category' => 'convert'],
            'image_to_svg' => ['name' => 'Image to SVG', 'desc' => 'Trace bitmap artwork into vector SVG', 'category' => 'convert'],
            
            'qr_generator' => ['name' => 'QR Generator', 'desc' => 'Create QR codes instantly', 'category' => 'utility'],
            'password_gen' => ['name' => 'Password Generator', 'desc' => 'Generate secure random passwords', 'category' => 'utility'],
            'word_counter' => ['name' => 'Word Counter', 'desc' => 'Count words and characters', 'category' => 'utility'],
            'image_compressor' => ['name' => 'Image Compressor', 'desc' => 'Compress and reduce image file sizes', 'category' => 'utility'],
            'resize_image' => ['name' => 'Resize Image', 'desc' => 'Resize and change image dimensions', 'category' => 'utility'],
            'crop_image' => ['name' => 'Crop Image', 'desc' => 'Crop screenshots and photos', 'category' => 'utility'],
            'image_enhancer' => ['name' => 'Image Enhancer', 'desc' => 'Upscale and sharpen blurry images', 'category' => 'utility'],
            'image_converter' => ['name' => 'Image Converter', 'desc' => 'Change JPG, PNG, and WEBP formats', 'category' => 'utility'],
            'heic_converter' => ['name' => 'HEIC to JPG PNG PDF', 'desc' => 'Convert HEIC images to JPG, PNG, or PDF', 'category' => 'utility'],
            'jpg_converter' => ['name' => 'JPG to PNG JPEG PDF', 'desc' => 'Convert JPG images to PNG, JPEG, or PDF', 'category' => 'utility'],
            'webp_converter' => ['name' => 'WEBP to PNG JPG JPEG PDF', 'desc' => 'Convert WEBP images to PNG, JPG, JPEG, or PDF', 'category' => 'utility'],
            'video_to_audio' => ['name' => 'Video to Audio', 'desc' => 'Convert video to MP3, WAV, AAC, OGG, or FLAC', 'category' => 'utility'],
            'video_compressor' => ['name' => 'Video Compressor', 'desc' => 'Compress video files into smaller MP4 output', 'category' => 'utility'],
            'bg_remover' => ['name' => 'Background Remover', 'desc' => 'Remove backgrounds to create transparent PNGs', 'category' => 'utility'],
            'image_to_dxf' => ['name' => 'Image to DXF', 'desc' => 'Trace bitmap images for CAD DXF files', 'category' => 'utility'],
            'ai_image_generator' => ['name' => 'AI Image Generator', 'desc' => 'Create images from prompts', 'category' => 'utility'],
            'ocr_tool' => ['name' => 'OCR Tool', 'desc' => 'Extract text from images', 'category' => 'utility'],
            'scan_to_pdf' => ['name' => 'Scan to PDF', 'desc' => 'Convert captured pages into a PDF', 'category' => 'utility'],
            
            'currency_converter' => ['name' => 'Currency Converter', 'desc' => 'Live exchange rates with daily updates', 'category' => 'conversion'],
            'length_converter' => ['name' => 'Length Converter', 'desc' => 'Convert km to millimeter and more', 'category' => 'conversion'],
            'weight_converter' => ['name' => 'Weight Converter', 'desc' => 'Convert kg, pounds, grams, and ounces', 'category' => 'conversion'],
            'temperature_converter' => ['name' => 'Temperature Converter', 'desc' => 'Convert Celsius, Fahrenheit, and Kelvin', 'category' => 'conversion'],
            'area_converter' => ['name' => 'Area Converter', 'desc' => 'Convert square feet, acres, hectares, and more', 'category' => 'conversion'],
            'volume_converter' => ['name' => 'Volume Converter', 'desc' => 'Convert liters, gallons, cups, and more', 'category' => 'conversion'],
            'speed_converter' => ['name' => 'Speed Converter', 'desc' => 'Convert km/h, mph, knots, and m/s', 'category' => 'conversion'],
            'time_converter' => ['name' => 'Time Converter', 'desc' => 'Convert seconds, minutes, hours, days, and years', 'category' => 'conversion'],
            
            'percentage_calculator' => ['name' => 'Percentage Calculator', 'desc' => 'Find percentages, rates, and quick value ratios', 'category' => 'calculator'],
            'loan_calculator' => ['name' => 'Loan Calculator', 'desc' => 'Calculate EMI, total payment, and total interest', 'category' => 'calculator'],
            'bmi_calculator' => ['name' => 'BMI Calculator', 'desc' => 'Check body mass index from height and weight', 'category' => 'calculator'],
            'age_calculator' => ['name' => 'Age Calculator', 'desc' => 'Calculate age in years and months from birth date', 'category' => 'calculator'],
            
            'invoice_generator' => ['name' => 'Invoice Generator', 'desc' => 'Create printable invoices with totals and tax', 'category' => 'business'],
            'ats_resume_checker' => ['name' => 'ATS Resume Checker', 'desc' => 'Compare your resume against a job description', 'category' => 'business'],
            'bank_statement_to_excel' => ['name' => 'Bank Statement PDF to Excel', 'desc' => 'Extract statement rows and export them to XLSX', 'category' => 'business'],
            'social_image_resizer' => ['name' => 'Social Image Resizer', 'desc' => 'Resize creatives for Instagram, YouTube, LinkedIn, and more', 'category' => 'business'],
            
            'grammar_checker' => ['name' => 'Grammar Checker', 'desc' => 'Clean spacing, punctuation, and casing issues', 'category' => 'writing'],
            'paraphrase_tool' => ['name' => 'Paraphrase Tool', 'desc' => 'Rewrite wording into a cleaner alternative phrasing', 'category' => 'writing'],
            
            'jwt_decoder' => ['name' => 'JWT Decoder', 'desc' => 'Decode token headers and payloads locally', 'category' => 'developer'],
            
            'sensitivity_converter' => ['name' => 'Sensitivity Converter', 'desc' => 'Convert sensitivity between major FPS games', 'category' => 'gaming'],
            'reaction_time_test' => ['name' => 'Reaction Time Test', 'desc' => 'Measure how quickly you react to a visual signal', 'category' => 'gaming'],
            'cps_test' => ['name' => 'CPS Test', 'desc' => 'Track clicks per second over a fast 5 second test', 'category' => 'gaming'],
            'gamer_tag_generator' => ['name' => 'Gamer Tag Generator', 'desc' => 'Generate modern usernames for gaming profiles', 'category' => 'gaming'],
            'clip_to_gif' => ['name' => 'Clip to GIF', 'desc' => 'Turn short gaming clips into shareable GIFs', 'category' => 'gaming'],
            'tournament_bracket_generator' => ['name' => 'Tournament Bracket Generator', 'desc' => 'Create a simple single-elimination bracket instantly', 'category' => 'gaming'],
            
            'spin_wheel' => ['name' => 'Spin the Wheel', 'desc' => 'Spin a colorful random choice wheel for fast decisions', 'category' => 'fun'],
            'random_name_picker' => ['name' => 'Random Name Picker', 'desc' => 'Pick random names for giveaways, classes, and lobbies', 'category' => 'fun'],
            'typing_speed_test' => ['name' => 'Typing Speed Test', 'desc' => 'Measure WPM and typing accuracy in the browser', 'category' => 'fun'],
            'meme_caption_generator' => ['name' => 'Meme Caption Generator', 'desc' => 'Add classic top and bottom meme captions to any image', 'category' => 'fun'],
            'truth_or_dare_generator' => ['name' => 'Truth or Dare Generator', 'desc' => 'Generate instant party prompts with one click', 'category' => 'fun'],
            'memory_match_game' => ['name' => 'Memory Match Game', 'desc' => 'Flip cards, match pairs, and beat your best time', 'category' => 'fun'],
        ];
    }

    /**
     * Generate rich WordPress-style HTML content for a specific post.
     */
    private static function generateContent(array $post): string
    {
        $toolId = $post['tool_id'];
        $title = $post['title'];
        $excerpt = $post['excerpt'];

        if ($toolId === null) {
            // Static content for legacy posts
            if ($post['slug'] === 'security-benefits') {
                return '
                <p class="lead">In today\'s digital landscape, document security is more crucial than ever. Many users often share photos (like receipts, IDs, and screenshots) in raw image formats (JPG/PNG), unaware of the security vulnerabilities associated with doing so. Transforming these images into a single, unified PDF document offers critical safety features.</p>
                
                <h2>1. Advanced Password Encryption</h2>
                <p>Unlike raw image formats, PDF files natively support industry-standard encryption algorithms (such as AES-256). By converting your photos into a PDF, you can set an owner password to control who can view, copy, print, or edit your documents, ensuring that sensitive credentials or identity cards don\'t fall into unauthorized hands.</p>
                
                <h2>2. Uniform Formatting & Anti-Tampering</h2>
                <p>When you send raw photos, they can easily be manipulated, cropped, or edited in paint software. A PDF locks the visual elements in place, making it considerably harder for bad actors to alter the content without leaving digital footprint traces. It also guarantees that the layout remains identical across all systems, whether the recipient is opening the document on an iPhone, an Android tablet, or a Windows workstation.</p>
                
                <div class="blog-note">
                    <strong>Pro Tip:</strong> When uploading scanned documents or receipts, always convert them to PDF first, then apply password restrictions.
                </div>

                <h2>3. Removal of Hidden Metadata (EXIF Data)</h2>
                <p>Raw photos captured on smartphones contain extensive EXIF metadata, including the exact GPS coordinates of where the photo was taken, the device model, camera settings, and timestamps. Uploading these images directly online exposes your personal privacy. A PDF conversion strips away this hidden camera metadata, making your files safe for public transmission.</p>
                ';
            }
            if ($post['slug'] === 'qr-guide') {
                return '
                <p class="lead">Quick Response (QR) codes have transitioned from high-tech novelties to everyday essentials for businesses worldwide. From contactless restaurant menus to app downloads and marketing landing pages, a QR code bridges the gap between physical media and digital experiences.</p>
                
                <h2>1. Optimal Contrast and Colors</h2>
                <p>For high scan reliability across older smartphones and budget devices, your QR code must have a high contrast ratio. A dark code on a white or light background is the industry standard. While custom colors are excellent for branding, avoid pastel colors or matching background tones, as this will fail in low-light environments.</p>
                
                <h2>2. Keep URL Slugs Short</h2>
                <p>The complexity of a QR code\'s pixel grid is directly related to the length of the data it contains. A long URL with multiple query parameters results in a dense, complex grid that is difficult for cameras to scan. Use a URL shortener or clean path redirects to keep the grid simple and quick to resolve.</p>
                
                <h2>3. Provide an Explicit Call to Action (CTA)</h2>
                <p>Never print a bare QR code. Users are cautious about scanning random codes due to security concerns. Always border your QR code with a clear action text like "Scan to View Menu" or "Scan for Free Wi-Fi". This establishes trust and increases engagement rates by over 40%.</p>
                ';
            }
        }

        $tool = self::getToolList()[$toolId] ?? ['name' => $post['title'], 'category' => 'utility', 'desc' => $excerpt];
        $name = $tool['name'];
        $category = $tool['category'];
        $desc = $tool['desc'];
        
        $categoryLabel = self::getCategoryLabel($category);

        return '
        <p class="lead">Handling digital tasks quickly and securely is a cornerstone of modern productivity. The <strong>' . $name . '</strong> tool on Any2Convert offers a free, lightweight, and local-first solution to help you ' . lcfirst($desc) . ' directly inside your web browser. No subscriptions, no hidden watermarks, and no software installations needed.</p>

        <h2>Why Use the Online ' . $name . ' Tool?</h2>
        <p>Most online converters require you to upload your files to remote cloud servers. This exposes your documents to privacy risks and slows down your workflow due to network upload and download bottlenecks. Any2Convert\'s ' . $name . ' tool processes your inputs locally using advanced JavaScript libraries. Your files remain on your device, ensuring maximum confidentiality and near-instant processing times.</p>

        <h2>Step-by-Step Guide: How to Use ' . $name . '</h2>
        <ol class="blog-steps">
            <li><strong>Access the Tool:</strong> Head over to the <a href="/' . $post['slug'] . '">Any2Convert ' . $name . '</a> page directly from our home directory.</li>
            <li><strong>Select or Input Your Data:</strong> Upload the files, paste the text, or configure the parameters as needed by the interface.</li>
            <li><strong>Configure Options:</strong> Fine-tune any custom parameters (such as compression levels, output formats, or layout dimensions).</li>
            <li><strong>Generate and Save:</strong> Click the process button and download the optimized output instantly back to your device.</li>
        </ol>

        <h2>Key Benefits of On-Device Processing</h2>
        <ul>
            <li><strong>Total Confidentiality:</strong> Perfect for corporate documents, personal screenshots, and private text information.</li>
            <li><strong>No Bandwidth Limitations:</strong> Since files do not need to be uploaded to a remote server, even large workflows resolve in a fraction of a second.</li>
            <li><strong>Consistent Accessibility:</strong> Works on desktop, mobile, and tablets across all major modern web browsers (Chrome, Safari, Firefox, Edge).</li>
        </ul>

        <div class="blog-note">
            <strong>Did you know?</strong> By running operations in your browser\'s local sandbox, Any2Convert protects you from common web vulnerabilities associated with server-side document converters.
        </div>

        <h2>Frequently Asked Questions</h2>
        <div class="blog-faq">
            <div class="faq-item">
                <h4>Is my data uploaded or stored on Any2Convert\'s servers?</h4>
                <p>No. For the ' . $name . ' tool, all processing happens locally in your web browser. We do not store, view, or log any of your files or inputs.</p>
            </div>
            <div class="faq-item">
                <h4>Are there any usage limits or formatting restrictions?</h4>
                <p>Our tools are completely free to use. There are no hourly limits, daily caps, or forced watermarks on your generated outputs.</p>
            </div>
            <div class="faq-item">
                <h4>Does this tool require an active internet connection?</h4>
                <p>Once the page is loaded in your browser, the local-first processing can run even if you temporarily lose internet connectivity, as all computation happens on your hardware.</p>
            </div>
        </div>
        ';
    }
}
