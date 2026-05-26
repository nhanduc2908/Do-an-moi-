import 'package:flutter/material.dart';

class ComplianceReportScreen extends StatelessWidget {
  const ComplianceReportScreen({super.key});

  final List<Map<String, dynamic>> _reports = const [
    {'name': 'ISO 27001 Compliance Report', 'date': '2024-01-15', 'format': 'PDF'},
    {'name': 'GDPR Readiness Assessment', 'date': '2024-01-10', 'format': 'PDF'},
    {'name': 'PCI DSS Gap Analysis', 'date': '2024-01-05', 'format': 'Excel'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Compliance Reports'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _reports.length,
        itemBuilder: (context, index) {
          final report = _reports[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: Container(
                width: 50,
                height: 50,
                decoration: BoxDecoration(
                  color: Colors.red.shade100,
                  borderRadius: BorderRadius.circular(8),
                ),
                child: const Icon(Icons.picture_as_pdf, color: Colors.red),
              ),
              title: Text(report['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Date: ${report['date']} • Format: ${report['format']}'),
              trailing: const Icon(Icons.download, color: Colors.blue),
              onTap: () {},
            ),
          );
        },
      ),
    );
  }
}