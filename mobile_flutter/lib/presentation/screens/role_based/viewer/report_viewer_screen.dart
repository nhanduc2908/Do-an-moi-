import 'package:flutter/material.dart';

class ReportViewerScreen extends StatefulWidget {
  const ReportViewerScreen({super.key});

  @override
  State<ReportViewerScreen> createState() => _ReportViewerScreenState();
}

class _ReportViewerScreenState extends State<ReportViewerScreen> {
  final List<Map<String, dynamic>> _reports = [
    {'name': 'Q4 2024 Security Summary', 'date': '2024-01-15', 'type': 'PDF', 'size': '2.4 MB'},
    {'name': 'Vulnerability Assessment Report', 'date': '2024-01-10', 'type': 'PDF', 'size': '3.1 MB'},
    {'name': 'Compliance Status Report', 'date': '2024-01-05', 'type': 'PDF', 'size': '1.8 MB'},
    {'name': 'Incident Response Summary', 'date': '2023-12-28', 'type': 'PDF', 'size': '1.2 MB'},
    {'name': 'Risk Assessment Report', 'date': '2023-12-20', 'type': 'PDF', 'size': '2.7 MB'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Report Viewer'),
        actions: [
          IconButton(
            icon: const Icon(Icons.search),
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
                child: const Icon(Icons.picture_as_pdf, color: Colors.red, size: 28),
              ),
              title: Text(report['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${report['date']} • ${report['size']}'),
              trailing: const Icon(Icons.visibility, color: Colors.blue),
              onTap: () {},
            ),
          );
        },
      ),
    );
  }
}