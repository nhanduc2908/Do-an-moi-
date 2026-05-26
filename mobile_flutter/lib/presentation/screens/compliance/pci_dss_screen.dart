import 'package:flutter/material.dart';

class PciDssScreen extends StatelessWidget {
  const PciDssScreen({super.key});

  final List<Map<String, dynamic>> _requirements = const [
    {'req': '1', 'name': 'Install and maintain firewall', 'status': 'Compliant'},
    {'req': '2', 'name': 'No default passwords', 'status': 'Compliant'},
    {'req': '3', 'name': 'Protect stored cardholder data', 'status': 'Partial'},
    {'req': '4', 'name': 'Encrypt transmission', 'status': 'Compliant'},
    {'req': '5', 'name': 'Use antivirus', 'status': 'Compliant'},
    {'req': '6', 'name': 'Secure systems', 'status': 'Non-Compliant'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('PCI DSS Compliance'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _requirements.length,
        itemBuilder: (context, index) {
          final req = _requirements[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 8),
            child: ListTile(
              leading: CircleAvatar(
                child: Text(req['req']),
              ),
              title: Text(req['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              trailing: Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                decoration: BoxDecoration(
                  color: _getStatusColor(req['status']).withOpacity(0.2),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  req['status'],
                  style: TextStyle(color: _getStatusColor(req['status'])),
                ),
              ),
            ),
          );
        },
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