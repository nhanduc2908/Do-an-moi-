// Đường dẫn: mobile_flutter/lib/presentation/screens/compliance/nist_screen.dart

import 'package:flutter/material.dart';

class NistScreen extends StatelessWidget {
  const NistScreen({super.key});

  final List<Map<String, dynamic>> _functions = const [
    {'name': 'Identify', 'score': 85, 'status': 'Good'},
    {'name': 'Protect', 'score': 72, 'status': 'Fair'},
    {'name': 'Detect', 'score': 68, 'status': 'Needs Improvement'},
    {'name': 'Respond', 'score': 78, 'status': 'Good'},
    {'name': 'Recover', 'score': 82, 'status': 'Good'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('NIST CSF'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _functions.length,
        itemBuilder: (context, index) {
          final function = _functions[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: _getScoreColor(function['score']),
                child: Text(function['name'].substring(0, 1), style: const TextStyle(color: Colors.white)),
              ),
              title: Text(function['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Score: ${function['score']}% • ${function['status']}'),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      LinearProgressIndicator(
                        value: function['score'] / 100,
                        backgroundColor: Colors.grey.shade200,
                      ),
                      const SizedBox(height: 12),
                      const Text('Recommendations:', style: TextStyle(fontWeight: FontWeight.bold)),
                      const Text('• Implement additional controls'),
                      const Text('• Regular assessment required'),
                    ],
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  Color _getScoreColor(int score) {
    if (score >= 80) return Colors.green;
    if (score >= 60) return Colors.orange;
    return Colors.red;
  }
}