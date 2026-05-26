import 'package:flutter/material.dart';

class CriteriaListScreen extends StatelessWidget {
  const CriteriaListScreen({super.key});

  final List<Map<String, dynamic>> _criteria = const [
    {'code': 'AC-001', 'name': 'User Registration Process', 'weight': 3, 'score': 4},
    {'code': 'AC-002', 'name': 'Unique User Identification', 'weight': 5, 'score': 5},
    {'code': 'AC-003', 'name': 'Access Review', 'weight': 4, 'score': 3},
    {'code': 'CR-001', 'name': 'Data Encryption at Rest', 'weight': 5, 'score': 4},
    {'code': 'CR-002', 'name': 'Data Encryption in Transit', 'weight': 5, 'score': 3},
    {'code': 'NS-001', 'name': 'Firewall Configuration', 'weight': 4, 'score': 5},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Assessment Criteria'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _criteria.length,
        itemBuilder: (context, index) {
          final criterion = _criteria[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 8),
            child: ListTile(
              leading: Container(
                width: 50,
                height: 50,
                decoration: BoxDecoration(
                  color: Colors.blue.shade100,
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Center(
                  child: Text(criterion['code'], style: const TextStyle(fontWeight: FontWeight.bold)),
                ),
              ),
              title: Text(criterion['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Weight: ${criterion['weight']} • Score: ${criterion['score']}/5'),
              trailing: const Icon(Icons.chevron_right),
              onTap: () {},
            ),
          );
        },
      ),
    );
  }
}