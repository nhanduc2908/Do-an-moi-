import 'package:flutter/material.dart';

class DomainListScreen extends StatelessWidget {
  const DomainListScreen({super.key});

  final List<Map<String, dynamic>> _domains = const [
    {'name': 'Access Control', 'code': 'AC', 'weight': 15, 'criteria': 45},
    {'name': 'Cryptography', 'code': 'CR', 'weight': 10, 'criteria': 32},
    {'name': 'Physical Security', 'code': 'PS', 'weight': 10, 'criteria': 28},
    {'name': 'Network Security', 'code': 'NS', 'weight': 15, 'criteria': 56},
    {'name': 'Application Security', 'code': 'AS', 'weight': 15, 'criteria': 48},
    {'name': 'Incident Response', 'code': 'IR', 'weight': 10, 'criteria': 35},
    {'name': 'Compliance', 'code': 'CM', 'weight': 15, 'criteria': 42},
    {'name': 'Risk Management', 'code': 'RM', 'weight': 10, 'criteria': 38},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Security Domains'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _domains.length,
        itemBuilder: (context, index) {
          final domain = _domains[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: Colors.blue.shade100,
                child: Text(domain['code'], style: const TextStyle(fontWeight: FontWeight.bold)),
              ),
              title: Text(domain['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Weight: ${domain['weight']}% • ${domain['criteria']} criteria'),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Description:', style: TextStyle(fontWeight: FontWeight.bold)),
                      const Text('Security controls for managing access and authentication.'),
                      const SizedBox(height: 8),
                      const Text('Progress:', style: TextStyle(fontWeight: FontWeight.bold)),
                      LinearProgressIndicator(value: 0.75, backgroundColor: Colors.grey.shade200),
                      const SizedBox(height: 8),
                      const Text('Score: 75%', style: TextStyle(fontWeight: FontWeight.bold)),
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