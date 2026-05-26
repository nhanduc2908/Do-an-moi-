// Đường dẫn: mobile_flutter/lib/presentation/screens/compliance/hipaa_screen.dart

import 'package:flutter/material.dart';

class HipaaScreen extends StatelessWidget {
  const HipaaScreen({super.key});

  final List<Map<String, dynamic>> _controls = const [
    {'rule': '164.308', 'name': 'Security Management Process', 'status': 'Compliant'},
    {'rule': '164.310', 'name': 'Physical Safeguards', 'status': 'Compliant'},
    {'rule': '164.312', 'name': 'Technical Safeguards', 'status': 'Partial'},
    {'rule': '164.314', 'name': 'Organizational Requirements', 'status': 'Non-Compliant'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('HIPAA Compliance'),
      ),
      body: Column(
        children: [
          Card(
            margin: const EdgeInsets.all(16),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  const Text('Overall Compliance Score', style: TextStyle(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  Stack(
                    alignment: Alignment.center,
                    children: [
                      SizedBox(
                        height: 120,
                        width: 120,
                        child: CircularProgressIndicator(
                          value: 0.75,
                          strokeWidth: 10,
                          backgroundColor: Colors.grey.shade200,
                          valueColor: const AlwaysStoppedAnimation<Color>(Colors.blue),
                        ),
                      ),
                      const Text('75%', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                    ],
                  ),
                ],
              ),
            ),
          ),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _controls.length,
              itemBuilder: (context, index) {
                final control = _controls[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: _getStatusColor(control['status']),
                      child: const Icon(Icons.rule, color: Colors.white),
                    ),
                    title: Text('${control['rule']}: ${control['name']}', style: const TextStyle(fontWeight: FontWeight.bold)),
                    trailing: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                      decoration: BoxDecoration(
                        color: _getStatusColor(control['status']).withOpacity(0.2),
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Text(
                        control['status'],
                        style: TextStyle(color: _getStatusColor(control['status'])),
                      ),
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Compliant': return Colors.green;
      case 'Partial': return Colors.orange;
      case 'Non-Compliant': return Colors.red;
      default: return Colors.grey;
    }
  }
}