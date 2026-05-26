import 'package:flutter/material.dart';
import '../../../core/routes/route_names.dart';

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  State<OnboardingScreen> createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  final List<Map<String, dynamic>> _pages = [
    {'title': 'Security Assessment', 'description': 'Comprehensive security assessment and compliance management', 'icon': Icons.security},
    {'title': 'Real-time Monitoring', 'description': 'Monitor threats and vulnerabilities in real-time', 'icon': Icons.analytics},
    {'title': 'AI-Powered Insights', 'description': 'Get intelligent recommendations and predictions', 'icon': Icons.psychology},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        children: [
          Expanded(
            child: PageView.builder(
              controller: _pageController,
              itemCount: _pages.length,
              onPageChanged: (index) => setState(() => _currentPage = index),
              itemBuilder: (context, index) {
                final page = _pages[index];
                return Padding(
                  padding: const EdgeInsets.all(32),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(page['icon'], size: 100, color: Colors.blue),
                      const SizedBox(height: 32),
                      Text(
                        page['title'],
                        style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                        textAlign: TextAlign.center,
                      ),
                      const SizedBox(height: 16),
                      Text(
                        page['description'],
                        style: const TextStyle(fontSize: 16, color: Colors.grey),
                        textAlign: TextAlign.center,
                      ),
                    ],
                  ),
                );
              },
            ),
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: List.generate(_pages.length, (index) => Container(
              margin: const EdgeInsets.all(4),
              width: 8,
              height: 8,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: _currentPage == index ? Colors.blue : Colors.grey,
              ),
            )),
          ),
          const SizedBox(height: 32),
          Padding(
            padding: const EdgeInsets.all(16),
            child: ElevatedButton(
              onPressed: () {
                if (_currentPage == _pages.length - 1) {
                  Navigator.pushReplacementNamed(context, RouteNames.login);
                } else {
                  _pageController.nextPage(
                    duration: const Duration(milliseconds: 300),
                    curve: Curves.easeInOut,
                  );
                }
              },
              style: ElevatedButton.styleFrom(
                minimumSize: const Size(double.infinity, 48),
              ),
              child: Text(_currentPage == _pages.length - 1 ? 'Get Started' : 'Next'),
            ),
          ),
        ],
      ),
    );
  }
}