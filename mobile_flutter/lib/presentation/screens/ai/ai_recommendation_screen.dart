import 'package:flutter/material.dart';

class AIRecommendationScreen extends StatelessWidget {
  const AIRecommendationScreen({super.key});

  final List<Map<String, dynamic>> _recommendations = const [
    {'priority': 'High', 'title': 'Update firewall rules', 'description': 'Configure firewall to block suspicious IP ranges', 'impact': 'Critical'},
    {'priority': 'Medium', 'title': 'Enable MFA', 'description': 'Implement multi-factor authentication for admin accounts', 'impact': 'High'},
    {'priority': 'Low', 'title': 'Update documentation', 'description': 'Review and update security policies', 'impact': 'Medium'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('AI Recommendations'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _recommendations.length,
        itemBuilder: (context, index) {
          final rec = _recommendations[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: rec['priority'] == 'High' ? Colors.red : (rec['priority'] == 'Medium' ? Colors.orange : Colors.blue),
                child: Icon(rec['priority'] == 'High' ? Icons.priority_high : Icons.lightbulb, color: Colors.white),
              ),
              title: Text(rec['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Impact: ${rec['impact']}'),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(rec['description']),
                      const SizedBox(height: 12),
                      ElevatedButton(
                        onPressed: () {},
                        child: const Text('View Details'),
                      ),
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
}