import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class SecurityScoreScreen extends StatefulWidget {
  const SecurityScoreScreen({super.key});

  @override
  State<SecurityScoreScreen> createState() => _SecurityScoreScreenState();
}

class _SecurityScoreScreenState extends State<SecurityScoreScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Security Score'),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  const Text('Overall Security Score', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  Stack(
                    alignment: Alignment.center,
                    children: [
                      SizedBox(
                        height: 180,
                        width: 180,
                        child: CircularProgressIndicator(
                          value: 0.78,
                          strokeWidth: 12,
                          backgroundColor: Colors.grey.shade200,
                          valueColor: const AlwaysStoppedAnimation<Color>(Colors.green),
                        ),
                      ),
                      Column(
                        children: [
                          const Text(
                            '78%',
                            style: TextStyle(fontSize: 36, fontWeight: FontWeight.bold),
                          ),
                          const Text('Good'),
                          const SizedBox(height: 4),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                            decoration: BoxDecoration(
                              color: Colors.green.shade100,
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: const Text('+5% from last month'),
                          ),
                        ],
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Category Scores', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  _buildCategoryScore('Access Control', 85, Colors.blue),
                  _buildCategoryScore('Cryptography', 70, Colors.orange),
                  _buildCategoryScore('Network Security', 82, Colors.green),
                  _buildCategoryScore('Application Security', 75, Colors.purple),
                  _buildCategoryScore('Incident Response', 68, Colors.red),
                  _buildCategoryScore('Compliance', 90, Colors.teal),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Recommendations', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  _buildRecommendation('Improve cryptography implementation', 'High'),
                  _buildRecommendation('Enhance incident response process', 'Medium'),
                  _buildRecommendation('Update access control policies', 'Low'),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCategoryScore(String name, int score, Color color) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(name, style: const TextStyle(fontWeight: FontWeight.w500)),
              Text('$score%', style: TextStyle(color: color, fontWeight: FontWeight.bold)),
            ],
          ),
          const SizedBox(height: 4),
          LinearProgressIndicator(
            value: score / 100,
            backgroundColor: Colors.grey.shade200,
            color: color,
            height: 8,
          ),
        ],
      ),
    );
  }

  Widget _buildRecommendation(String text, String priority) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Container(
            width: 8,
            height: 8,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: priority == 'High' ? Colors.red : (priority == 'Medium' ? Colors.orange : Colors.green),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(child: Text(text)),
        ],
      ),
    );
  }
}