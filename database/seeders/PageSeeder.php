<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // About Page
        Page::create([
            'title' => 'About Us',
            'slug' => 'about',
            'content' => 'ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a complete account of the system, and expound the actual teachings of the eat explorer of the truth, the mer of human.',
            'sections' => [
                'hero' => [
                    'title' => 'WELCOME TO ARHAM E-COMMERCE.',
                    'description' => 'ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a complete account of the system, and expound the actual teachings of the eat explorer of the truth, the mer of human.',
                    'image' => 'images/banners/about-banner.webp',
                ],
                'award' => [
                    'title' => 'WIN BEST ONLINE SHOP AT 2024',
                    'description' => 'ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a complete account of the system, and expound the actual teachings of the eat explorer of the truth, the mer of human.',
                ],
                'banners' => [
                    ['image' => 'images/banners/home3-banner1.webp', 'link' => '/catalog'],
                    ['image' => 'images/banners/home3-banner2.webp', 'link' => '/catalog'],
                    ['image' => 'images/banners/home3-banner3.webp', 'link' => '/catalog'],
                ],
                'mission' => [
                    'title' => 'OUR MISSION',
                    'description' => 'ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a ete account of the system, and expound the actual teangs the eat explorer of the truth, the mer of human tas assumenda est, omnis dolor repellend',
                ],
                'vision' => [
                    'title' => 'OUR VISSION',
                    'description' => 'ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a ete account of the system, and expound the actual teangs the eat explorer of the truth, the mer of human tas assumenda est, omnis dolor repellend',
                ],
                'goal' => [
                    'title' => 'OUR GOAL',
                    'description' => 'ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born an will give you a ete account of the system, and expound the actual teangs the eat explorer of the truth, the mer of human tas assumenda est, omnis dolor repellend',
                ],
                'why_choose' => [
                    'title' => 'YOU CAN CHOOSE US BECAUSE WE ALWAYS PROVIDE IMPORTANCE...',
                    'description' => 'ARHAM E-COMMERCE provide how all this mistaken idea of denouncing pleasure and sing pain was born will give you a complete account of the system, and expound the actual',
                    'features' => [
                        ['title' => 'FAST DELIVERY', 'description' => 'ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing'],
                        ['title' => 'QUALITY PRODUCT', 'description' => 'ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing'],
                        ['title' => 'SECURE PAYMENT', 'description' => 'ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing'],
                        ['title' => 'MONEY BACK GUARNTEE', 'description' => 'ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing'],
                        ['title' => 'EASY ORDER TRACKING', 'description' => 'ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing'],
                        ['title' => 'FREE RETURN', 'description' => 'ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing'],
                        ['title' => '24/7 SUPPORT', 'description' => 'ARHAM E-COMMERCE provide how all this mistaken dea of denouncing pleasure and sing'],
                    ],
                    'banner' => 'images/banners/home3-banner8.webp',
                ],
            ],
            'meta' => [
                'description' => 'Learn more about Arham E-Commerce - Your trusted online shopping destination',
                'keywords' => 'about, arham, e-commerce, online shop, mission, vision',
            ],
            'template' => 'about',
            'is_active' => true,
            'show_in_footer' => true,
            'footer_order' => 1,
        ]);

        // Privacy Policy
        Page::create([
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'content' => '<h3>Privacy Policy</h3><p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information.</p><h4>Information We Collect</h4><p>We collect information that you provide directly to us when you create an account, make a purchase, or contact us.</p>',
            'meta' => [
                'description' => 'Read our privacy policy to understand how we protect your data',
                'keywords' => 'privacy, policy, data protection, security',
            ],
            'template' => 'default',
            'is_active' => true,
            'show_in_footer' => true,
            'footer_order' => 2,
        ]);

        // Terms & Conditions
        Page::create([
            'title' => 'Terms & Conditions',
            'slug' => 'terms-conditions',
            'content' => '<h3>Terms & Conditions</h3><p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p><h4>Use License</h4><p>Permission is granted to temporarily download one copy of the materials on our website for personal, non-commercial transitory viewing only.</p>',
            'meta' => [
                'description' => 'Read our terms and conditions before using our services',
                'keywords' => 'terms, conditions, agreement, rules',
            ],
            'template' => 'default',
            'is_active' => true,
            'show_in_footer' => true,
            'footer_order' => 3,
        ]);

        // FAQ
        Page::create([
            'title' => 'FAQ',
            'slug' => 'faq',
            'content' => '<h3>Frequently Asked Questions</h3><p>Find answers to common questions about our products and services.</p>',
            'sections' => [
                'faqs' => [
                    ['question' => 'How do I place an order?', 'answer' => 'Simply browse our catalog, add items to your cart, and proceed to checkout.'],
                    ['question' => 'What payment methods do you accept?', 'answer' => 'We accept credit cards, bank transfers, and various e-wallet payments through Midtrans.'],
                    ['question' => 'How long does shipping take?', 'answer' => 'Shipping typically takes 2-5 business days depending on your location.'],
                    ['question' => 'Can I return a product?', 'answer' => 'Yes, we offer a 7-day return policy for most products. See our return policy for details.'],
                ],
            ],
            'meta' => [
                'description' => 'Frequently asked questions about Arham E-Commerce',
                'keywords' => 'faq, questions, help, support',
            ],
            'template' => 'default',
            'is_active' => true,
            'show_in_footer' => true,
            'footer_order' => 4,
        ]);

        // Contact Us
        Page::create([
            'title' => 'Contact Us',
            'slug' => 'contact',
            'content' => '<h3>Get In Touch</h3><p>Have questions? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.</p>',
            'sections' => [
                'contact_info' => [
                    'address' => 'Jl. Example No. 123, Jakarta, Indonesia',
                    'phone' => '+62 812-3456-7890',
                    'email' => 'info@arham-ecommerce.com',
                    'hours' => 'Monday - Friday: 9:00 AM - 6:00 PM',
                ],
            ],
            'meta' => [
                'description' => 'Contact Arham E-Commerce for any questions or support',
                'keywords' => 'contact, support, help, customer service',
            ],
            'template' => 'default',
            'is_active' => true,
            'show_in_footer' => true,
            'footer_order' => 5,
        ]);
    }
}
