// Đường dẫn: mobile_flutter/lib/presentation/screens/security/source_code_security_screen.dart

import 'package:flutter/material.dart';

class SourceCodeSecurityScreen extends StatelessWidget {
  const SourceCodeSecurityScreen({super.key});

  final List<Map<String, dynamic>> _findings = const [
    {'file': 'auth.dart', 'issue': 'Hardcoded credentials', 'severity': 'Critical', 'line': 45, 'status': 'Open'},
    {'file': 'api.dart', 'issue': 'SQL Injection vulnerability', 'severity': 'High', 'line': 120, 'status': 'In Progress'},
    {'file': 'config.dart', 'issue': 'API key exposed', 'severity': 'Critical', 'line': 23, 'status': 'Open'},
    {'file': 'database.dart', 'issue': 'Weak encryption', 'severity': 'Medium', 'line': 78, 'status': 'Fixed'},
    {'file': 'auth.dart', 'issue': 'Missing input validation', 'severity': 'High', 'line': 156, 'status': 'In Progress'},
    {'file': 'crypto.dart', 'issue': 'Use of deprecated algorithm', 'severity': 'Medium', 'line': 34, 'status': 'Open'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Source Code Security'),
        actions: [
          IconButton(
            icon: const Icon(Icons.upload),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.code),
            onPressed: () {},
          ),
        ],
      ),
      body: Column(
        children: [
          Card(
            margin: const EdgeInsets.all(16),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  const Text('Code Security Score', style: TextStyle(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  Stack(
                    alignment: Alignment.center,
                    children: [
                      SizedBox(
                        height: 100,
                        width: 100,
                        child: CircularProgressIndicator(
                          value: 0.68,
                          strokeWidth: 8,
                          backgroundColor: Colors.grey.shade200,
                          valueColor: const AlwaysStoppedAnimation<Color>(Colors.orange),
                        ),
                      ),
                      const Text('68%', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  const SizedBox(height: 8),
                  const Text('Medium Risk', style: TextStyle(color: Colors.orange)),
                ],
              ),
            ),
          ),
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 16),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text('Findings', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                Text('6 issues', style: TextStyle(color: Colors.grey)),
              ],
            ),
          ),
          const SizedBox(height: 8),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _findings.length,
              itemBuilder: (context, index) {
                final finding = _findings[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: _getSeverityColor(finding['severity']),
                      child: const Icon(Icons.code, color: Colors.white),
                    ),
                    title: Text('${finding['file']}:${finding['line']}', style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Text(finding['issue']),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                          decoration: BoxDecoration(
                            color: _getStatusColor(finding['status']).withOpacity(0.2),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Text(
                            finding['status'],
                            style: TextStyle(color: _getStatusColor(finding['status']), fontSize: 12),
                          ),
                        ),
                        const SizedBox(width: 8),
                        Chip(
                          label: Text(finding['severity']),
                          backgroundColor: _getSeverityColor(finding['severity']).withOpacity(0.2),
                        ),
                      ],
                    ),
                    onTap: () {},
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Color _getSeverityColor(String severity) {
    switch (severity) {
      case 'Critical': return Colors.red;
      case 'High': return Colors.orange;
      case 'Medium': return Colors.yellow.shade700;
      default: return Colors.green;
    }
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Open': return Colors.red;
      case 'In Progress': return Colors.orange;
      case 'Fixed': return Colors.green;
      default: return Colors.grey;
    }
  }
}