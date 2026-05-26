import 'package:flutter/material.dart';

class ScanResultScreen extends StatelessWidget {
  const ScanResultScreen({super.key});

  final List<Map<String, dynamic>> _results = const [
    {'type': 'Vulnerability', 'name': 'SQL Injection', 'severity': 'High', 'url': '/login'},
    {'type': 'Warning', 'name': 'Missing Security Headers', 'severity': 'Medium', 'url': '/'},
    {'type': 'Info', 'name': 'SSL Certificate Valid', 'severity': 'Low', 'url': '/'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Scan Results'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _results.length,
        itemBuilder: (context, index) {
          final result = _results[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 8),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: result['severity'] == 'High' ? Colors.red : 
                                 (result['severity'] == 'Medium' ? Colors.orange : Colors.blue),
                child: const Icon(Icons.warning, color: Colors.white),
              ),
              title: Text(result['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${result['type']} • ${result['url']}'),
              trailing: Chip(label: Text(result['severity'])),
            ),
          );
        },
      ),
    );
  }
}