<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventSummary;
use App\Models\EventTheme;
use App\Models\EventProgramme;
use App\Models\EventResource;
use App\Models\Speaker;
use App\Models\Topic;
use App\Models\FAQ;
use App\Models\Media;
use App\Models\Sponsor;
use App\Models\Gallery;
use App\Models\Attendance;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 2024 Event
        $event2024 = Event::create([
            'year' => 2024,
            'title' => 'Tech Innovation Summit 2024',
            'location' => 'Grand Convention Center, New York',
            'start_date' => '2024-09-15',
            'end_date' => '2024-09-17',
            'is_published' => true,
        ]);

        // Add summaries for 2024
        EventSummary::create([
            'event_id' => $event2024->id,
            'summary' => 'Join us for the premier technology conference of 2024, where industry leaders, innovators, and developers come together to explore the latest trends in artificial intelligence, cloud computing, and digital transformation. This three-day event features keynote speeches, hands-on workshops, and networking opportunities with top tech professionals.',
        ]);

        // Add themes for 2024
        EventTheme::create([
            'event_id' => $event2024->id,
            'theme' => 'AI & Machine Learning',
            'description' => 'Exploring the frontiers of artificial intelligence and its impact on business and society',
        ]);

        EventTheme::create([
            'event_id' => $event2024->id,
            'theme' => 'Cloud Computing',
            'description' => 'Advanced cloud architectures and serverless technologies',
        ]);

        EventTheme::create([
            'event_id' => $event2024->id,
            'theme' => 'Cybersecurity',
            'description' => 'Protecting digital assets in an evolving threat landscape',
        ]);

        // Add programme items for 2024
        EventProgramme::create([
            'event_id' => $event2024->id,
            'title' => 'Opening Keynote: The Future of AI',
            'description' => 'Discover how AI is reshaping industries and creating new opportunities',
            'start_time' => '2024-09-15 09:00:00',
            'end_time' => '2024-09-15 10:30:00',
            'location' => 'Main Hall A',
            'speaker' => 'Dr. Sarah Johnson',
            'order' => 1,
        ]);

        EventProgramme::create([
            'event_id' => $event2024->id,
            'title' => 'Workshop: Building Scalable Microservices',
            'description' => 'Hands-on workshop on modern microservices architecture',
            'start_time' => '2024-09-15 11:00:00',
            'end_time' => '2024-09-15 13:00:00',
            'location' => 'Workshop Room 1',
            'speaker' => 'Michael Chen',
            'order' => 2,
        ]);

        EventProgramme::create([
            'event_id' => $event2024->id,
            'title' => 'Panel Discussion: Cloud Security Best Practices',
            'description' => 'Expert panel discussing enterprise cloud security strategies',
            'start_time' => '2024-09-15 14:00:00',
            'end_time' => '2024-09-15 15:30:00',
            'location' => 'Main Hall B',
            'speaker' => 'Multiple Speakers',
            'order' => 3,
        ]);

        // Add topics for 2024
        $topic2024_ai = Topic::create([
            'event_id' => $event2024->id,
            'title' => 'The Future of Artificial Intelligence',
            'topic_date' => '2024-09-15',
            'content' => 'Exploring how AI is transforming industries and creating new opportunities. This session covers machine learning, deep learning, natural language processing, and the ethical implications of AI in modern society.',
            'topic_picture' => '/images/topics/ai-future.jpg',
            'order' => 1,
        ]);

        $topic2024_cloud = Topic::create([
            'event_id' => $event2024->id,
            'title' => 'Cloud Architecture & Scalability',
            'topic_date' => '2024-09-16',
            'content' => 'Learn about modern cloud architectures, microservices, serverless computing, and how to build scalable applications that can handle millions of users.',
            'topic_picture' => '/images/topics/cloud-architecture.jpg',
            'order' => 2,
        ]);

        $topic2024_security = Topic::create([
            'event_id' => $event2024->id,
            'title' => 'Cybersecurity in the Digital Age',
            'topic_date' => '2024-09-17',
            'content' => 'Understanding the evolving threat landscape and implementing robust security measures to protect your organization from cyber attacks.',
            'topic_picture' => '/images/topics/cybersecurity.jpg',
            'order' => 3,
        ]);

        // Add speakers for 2024 (linked to topics)
        Speaker::create([
            'event_id' => $event2024->id,
            'topic_id' => $topic2024_ai->id,
            'name' => 'Dr. Sarah Johnson',
            'title' => 'Chief AI Scientist',
            'organization' => 'TechVision Labs',
            'bio' => 'Dr. Johnson is a leading expert in artificial intelligence with over 15 years of experience in machine learning and neural networks. She has published over 50 research papers and holds multiple patents in AI technology.',
            'photo' => '/images/speakers/sarah-johnson.jpg',
            'email' => 'sarah.johnson@techvision.com',
            'linkedin' => 'https://linkedin.com/in/sarahjohnson',
            'twitter' => '@drsarahai',
            'order' => 1,
        ]);

        Speaker::create([
            'event_id' => $event2024->id,
            'topic_id' => $topic2024_cloud->id,
            'name' => 'Michael Chen',
            'title' => 'Senior Solutions Architect',
            'organization' => 'CloudScale Systems',
            'bio' => 'Michael specializes in designing and implementing large-scale cloud infrastructure. He has helped numerous Fortune 500 companies migrate to cloud-native architectures.',
            'photo' => '/images/speakers/michael-chen.jpg',
            'email' => 'michael.chen@cloudscale.com',
            'linkedin' => 'https://linkedin.com/in/michaelchen',
            'twitter' => '@mchen_cloud',
            'order' => 2,
        ]);

        Speaker::create([
            'event_id' => $event2024->id,
            'topic_id' => $topic2024_security->id,
            'name' => 'Emily Rodriguez',
            'title' => 'Cybersecurity Director',
            'organization' => 'SecureNet Inc',
            'bio' => 'Emily is a renowned cybersecurity expert with extensive experience in threat detection and incident response. She regularly speaks at international security conferences.',
            'photo' => '/images/speakers/emily-rodriguez.jpg',
            'email' => 'emily.rodriguez@securenet.com',
            'linkedin' => 'https://linkedin.com/in/emilyrodriguez',
            'order' => 3,
        ]);

        // Additional speaker for AI topic (showing one topic can have multiple speakers)
        Speaker::create([
            'event_id' => $event2024->id,
            'topic_id' => $topic2024_ai->id,
            'name' => 'Prof. David Martinez',
            'title' => 'Professor of Computer Science',
            'organization' => 'MIT',
            'bio' => 'Professor Martinez teaches AI and machine learning at MIT and has contributed to numerous AI research projects.',
            'photo' => '/images/speakers/david-martinez.jpg',
            'email' => 'david.martinez@mit.edu',
            'linkedin' => 'https://linkedin.com/in/davidmartinez',
            'twitter' => '@profmartinez',
            'order' => 4,
        ]);

        // Add sponsors for 2024
        Sponsor::create([
            'event_id' => $event2024->id,
            'name' => 'TechCorp Global',
            'tier' => 'Platinum',
            'logo' => '/images/sponsors/techcorp.png',
            'website' => 'https://techcorp.com',
            'description' => 'Leading provider of enterprise software solutions',
            'order' => 1,
        ]);

        Sponsor::create([
            'event_id' => $event2024->id,
            'name' => 'CloudBase Solutions',
            'tier' => 'Gold',
            'logo' => '/images/sponsors/cloudbase.png',
            'website' => 'https://cloudbase.com',
            'description' => 'Cloud infrastructure and hosting services',
            'order' => 2,
        ]);

        Sponsor::create([
            'event_id' => $event2024->id,
            'name' => 'DataStream Analytics',
            'tier' => 'Silver',
            'logo' => '/images/sponsors/datastream.png',
            'website' => 'https://datastream.com',
            'description' => 'Big data analytics and business intelligence',
            'order' => 3,
        ]);

        // Add FAQs for 2024
        FAQ::create([
            'event_id' => $event2024->id,
            'question' => 'What time does registration start?',
            'answer' => 'Registration opens at 8:00 AM on the first day of the event. We recommend arriving early to avoid queues.',
            'order' => 1,
        ]);

        FAQ::create([
            'event_id' => $event2024->id,
            'question' => 'Is WiFi available at the venue?',
            'answer' => 'Yes, free high-speed WiFi is available throughout the convention center. Network credentials will be provided upon registration.',
            'order' => 2,
        ]);

        FAQ::create([
            'event_id' => $event2024->id,
            'question' => 'What is the dress code?',
            'answer' => 'Business casual attire is recommended. The venue is climate-controlled for your comfort.',
            'order' => 3,
        ]);

        FAQ::create([
            'event_id' => $event2024->id,
            'question' => 'Are meals included?',
            'answer' => 'Yes, lunch and refreshments are provided for all attendees. Please inform us of any dietary restrictions during registration.',
            'order' => 4,
        ]);

        // Add resources for 2024
        EventResource::create([
            'event_id' => $event2024->id,
            'title' => 'Event Program Guide',
            'description' => 'Complete schedule and session details',
            'file_path' => '/resources/2024/program-guide.pdf',
            'file_type' => 'PDF',
            'url' => null,
        ]);

        EventResource::create([
            'event_id' => $event2024->id,
            'title' => 'Venue Map',
            'description' => 'Floor plan and room locations',
            'file_path' => '/resources/2024/venue-map.pdf',
            'file_type' => 'PDF',
            'url' => null,
        ]);

        // Add media for 2024
        Media::create([
            'event_id' => $event2024->id,
            'title' => 'Keynote Highlights',
            'type' => 'video',
            'file_path' => '/media/2024/keynote-highlights.mp4',
            'thumbnail' => '/media/2024/thumbnails/keynote.jpg',
            'description' => 'Highlights from the opening keynote session',
            'order' => 1,
        ]);

        Media::create([
            'event_id' => $event2024->id,
            'title' => 'Event Promotional Video',
            'type' => 'video',
            'file_path' => '/media/2024/promo-video.mp4',
            'thumbnail' => '/media/2024/thumbnails/promo.jpg',
            'description' => 'Official promotional video for Tech Summit 2024',
            'order' => 2,
        ]);

        // Add gallery images for 2024
        Gallery::create([
            'event_id' => $event2024->id,
            'title' => 'Opening Ceremony',
            'image_path' => '/gallery/2024/opening-ceremony.jpg',
            'caption' => 'Attendees gathering for the opening ceremony',
            'order' => 1,
        ]);

        Gallery::create([
            'event_id' => $event2024->id,
            'title' => 'Workshop Session',
            'image_path' => '/gallery/2024/workshop-1.jpg',
            'caption' => 'Participants engaged in hands-on workshop',
            'order' => 2,
        ]);

        Gallery::create([
            'event_id' => $event2024->id,
            'title' => 'Networking Break',
            'image_path' => '/gallery/2024/networking.jpg',
            'caption' => 'Attendees networking during coffee break',
            'order' => 3,
        ]);

        // Add attendance records for 2024
        Attendance::create([
            'event_id' => $event2024->id,
            'name' => 'John Smith',
            'email' => 'john.smith@example.com',
            'phone' => '+1-555-0101',
            'organization' => 'Tech Innovations Inc',
            'registration_type' => 'VIP',
            'checked_in' => true,
            'checked_in_at' => '2024-09-15 08:30:00',
        ]);

        Attendance::create([
            'event_id' => $event2024->id,
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'phone' => '+1-555-0102',
            'organization' => 'Digital Solutions LLC',
            'registration_type' => 'Regular',
            'checked_in' => true,
            'checked_in_at' => '2024-09-15 08:45:00',
        ]);

        Attendance::create([
            'event_id' => $event2024->id,
            'name' => 'Robert Brown',
            'email' => 'robert.brown@example.com',
            'phone' => '+1-555-0103',
            'organization' => 'StartupHub',
            'registration_type' => 'Student',
            'checked_in' => false,
            'checked_in_at' => null,
        ]);

        // ===== Create 2025 Event =====
        $event2025 = Event::create([
            'year' => 2025,
            'title' => 'Global Tech Conference 2025',
            'location' => 'International Expo Center, San Francisco',
            'start_date' => '2025-10-20',
            'end_date' => '2025-10-22',
            'is_published' => true,
        ]);

        EventSummary::create([
            'event_id' => $event2025->id,
            'summary' => 'The most anticipated tech conference of 2025! Join thousands of developers, entrepreneurs, and tech enthusiasts for three days of cutting-edge presentations, interactive workshops, and unparalleled networking opportunities. Explore the future of technology with blockchain, quantum computing, and sustainable tech innovations.',
        ]);

        EventTheme::create([
            'event_id' => $event2025->id,
            'theme' => 'Quantum Computing',
            'description' => 'The next frontier in computational power and problem-solving',
        ]);

        EventTheme::create([
            'event_id' => $event2025->id,
            'theme' => 'Blockchain & Web3',
            'description' => 'Decentralized technologies reshaping digital economy',
        ]);

        // Add topics for 2025
        $topic2025_quantum = Topic::create([
            'event_id' => $event2025->id,
            'title' => 'Quantum Computing Revolution',
            'topic_date' => '2025-10-20',
            'content' => 'Discover the power of quantum computing and how it will solve problems that classical computers cannot. Learn about qubits, quantum algorithms, and real-world applications.',
            'topic_picture' => '/images/topics/quantum-computing.jpg',
            'order' => 1,
        ]);

        $topic2025_blockchain = Topic::create([
            'event_id' => $event2025->id,
            'title' => 'Blockchain & Web3 Technologies',
            'topic_date' => '2025-10-21',
            'content' => 'Explore decentralized applications, smart contracts, NFTs, and how blockchain is transforming industries from finance to healthcare.',
            'topic_picture' => '/images/topics/blockchain.jpg',
            'order' => 2,
        ]);

        Speaker::create([
            'event_id' => $event2025->id,
            'topic_id' => $topic2025_quantum->id,
            'name' => 'Dr. James Wilson',
            'title' => 'Quantum Research Lead',
            'organization' => 'Quantum Dynamics Corp',
            'bio' => 'Pioneer in quantum computing with groundbreaking research in quantum algorithms',
            'email' => 'james.wilson@quantumdynamics.com',
            'order' => 1,
        ]);

        Speaker::create([
            'event_id' => $event2025->id,
            'topic_id' => $topic2025_blockchain->id,
            'name' => 'Alex Thompson',
            'title' => 'Blockchain Architect',
            'organization' => 'CryptoSolutions Inc',
            'bio' => 'Expert in blockchain technology with experience building decentralized applications on Ethereum and Solana',
            'email' => 'alex.thompson@cryptosolutions.com',
            'order' => 2,
        ]);

        Sponsor::create([
            'event_id' => $event2025->id,
            'name' => 'FutureTech Industries',
            'tier' => 'Platinum',
            'website' => 'https://futuretech.com',
            'description' => 'Innovation leaders in emerging technologies',
            'order' => 1,
        ]);

        FAQ::create([
            'event_id' => $event2025->id,
            'question' => 'Will sessions be recorded?',
            'answer' => 'Yes, all keynote sessions will be recorded and made available to registered attendees within 48 hours.',
            'order' => 1,
        ]);

        // ===== Create 2026 Event (Current) =====
        $event2026 = Event::create([
            'year' => 2026,
            'title' => 'Future Tech Symposium 2026',
            'location' => 'Tech Park Convention Hall, Austin',
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-12',
            'is_published' => true,
        ]);

        EventSummary::create([
            'event_id' => $event2026->id,
            'summary' => 'Step into the future at the 2026 Tech Symposium! This year we focus on sustainable technology, AI ethics, and the metaverse. Connect with visionaries who are building tomorrow\'s technology today. Early bird registration now open!',
        ]);

        EventTheme::create([
            'event_id' => $event2026->id,
            'theme' => 'Sustainable Technology',
            'description' => 'Green tech solutions for a sustainable future',
        ]);

        EventTheme::create([
            'event_id' => $event2026->id,
            'theme' => 'AI Ethics',
            'description' => 'Responsible AI development and deployment',
        ]);

        EventTheme::create([
            'event_id' => $event2026->id,
            'theme' => 'Metaverse & Virtual Worlds',
            'description' => 'Immersive technologies and virtual experiences',
        ]);

        // Add topics for 2026
        $topic2026_ethics = Topic::create([
            'event_id' => $event2026->id,
            'title' => 'AI Ethics & Responsible Development',
            'topic_date' => '2026-11-10',
            'content' => 'Discussing the ethical implications of AI development and deployment. How can we ensure AI systems are fair, transparent, and accountable? This session covers bias in AI, privacy concerns, and regulatory frameworks.',
            'topic_picture' => '/images/topics/ai-ethics.jpg',
            'order' => 1,
        ]);

        $topic2026_sustainable = Topic::create([
            'event_id' => $event2026->id,
            'title' => 'Sustainable Technology Solutions',
            'topic_date' => '2026-11-11',
            'content' => 'Green technology and sustainable solutions for reducing carbon footprint in tech industry. Topics include renewable energy in data centers, e-waste management, and eco-friendly software development.',
            'topic_picture' => '/images/topics/sustainable-tech.jpg',
            'order' => 2,
        ]);

        $topic2026_metaverse = Topic::create([
            'event_id' => $event2026->id,
            'title' => 'Building the Metaverse',
            'topic_date' => '2026-11-12',
            'content' => 'Exploring virtual worlds, augmented reality, and immersive experiences. Learn about VR/AR technologies, 3D environments, and the future of digital interaction.',
            'topic_picture' => '/images/topics/metaverse.jpg',
            'order' => 3,
        ]);

        Speaker::create([
            'event_id' => $event2026->id,
            'topic_id' => $topic2026_ethics->id,
            'name' => 'Dr. Lisa Martinez',
            'title' => 'AI Ethics Researcher',
            'organization' => 'Ethics in Tech Institute',
            'bio' => 'Leading voice in AI ethics and responsible technology development',
            'email' => 'lisa.martinez@ethicsintech.org',
            'order' => 1,
        ]);

        Speaker::create([
            'event_id' => $event2026->id,
            'topic_id' => $topic2026_sustainable->id,
            'name' => 'Jennifer Green',
            'title' => 'Sustainability Officer',
            'organization' => 'GreenTech Solutions',
            'bio' => 'Expert in sustainable technology with focus on reducing environmental impact of digital infrastructure',
            'email' => 'jennifer.green@greentech.com',
            'order' => 2,
        ]);

        Speaker::create([
            'event_id' => $event2026->id,
            'topic_id' => $topic2026_metaverse->id,
            'name' => 'Robert Kim',
            'title' => 'VR/AR Developer',
            'organization' => 'MetaVerse Studios',
            'bio' => 'Pioneer in virtual reality development with experience creating immersive 3D environments',
            'email' => 'robert.kim@metaverse.com',
            'order' => 3,
        ]);

        // Additional speaker for AI Ethics topic (showing one-to-many relationship)
        Speaker::create([
            'event_id' => $event2026->id,
            'topic_id' => $topic2026_ethics->id,
            'name' => 'Prof. John Anderson',
            'title' => 'Professor of Philosophy',
            'organization' => 'Stanford University',
            'bio' => 'Philosopher specializing in technology ethics and AI governance frameworks',
            'email' => 'john.anderson@stanford.edu',
            'order' => 4,
        ]);

        Sponsor::create([
            'event_id' => $event2026->id,
            'name' => 'GreenTech Solutions',
            'tier' => 'Gold',
            'website' => 'https://greentech.com',
            'description' => 'Sustainable technology and renewable energy solutions',
            'order' => 1,
        ]);

        FAQ::create([
            'event_id' => $event2026->id,
            'question' => 'Is there parking available?',
            'answer' => 'Yes, complimentary parking is available for all attendees at the convention center parking garage.',
            'order' => 1,
        ]);

        EventProgramme::create([
            'event_id' => $event2026->id,
            'title' => 'Future of AI Ethics',
            'description' => 'Exploring ethical considerations in AI development',
            'start_time' => '2026-11-10 10:00:00',
            'end_time' => '2026-11-10 11:30:00',
            'location' => 'Main Auditorium',
            'speaker' => 'Dr. Lisa Martinez',
            'order' => 1,
        ]);

        Attendance::create([
            'event_id' => $event2026->id,
            'name' => 'Sarah Johnson',
            'email' => 'sarah.j@example.com',
            'phone' => '+1-555-0201',
            'organization' => 'Tech Innovators',
            'registration_type' => 'VIP',
            'checked_in' => false,
        ]);
    }
}
