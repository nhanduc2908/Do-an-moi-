import 'package:flutter/material.dart';

class WebSecurityScreen extends StatelessWidget {
  const WebSecurityScreen({super.key});

  final List<Map<String, dynamic>> _findings = const [
    {'issue': 'Missing Security Headers', 'severity': 'Medium', 'remediation': 'Add X-Frame-Options header'},
    {'issue': 'SSL Certificate Expiring', 'severity': 'High', 'remediation': 'Renew SSL certificate'},
    {'issue': 'Open Ports', 'severity': 'Critical', 'remediation': 'Close unnecessary ports'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Web Security'),
      ),
      body: ListView(
        children: [
          Card(
            margin: const EdgeInsets.all(16),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  const Text('Security Score: 72%', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  LinearProgressIndicator(value: 0.72, backgroundColor: Colors.grey.shade200),
                ],
              ),
            ),
          ),
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 16),
            child: Text('Findings', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          ),
          ..._findings.map((finding) => Card(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: finding['severity'] == 'Critical' ? Colors.red : (finding['severity'] == 'High' ? Colors.orange : Colors.yellow),
                child: const Icon(Icons.warning, color: Colors.white),
              ),
              title: Text(finding['issue'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text(finding['remediation']),
              trailing: Chip(label: Text(finding['severity'])),
            ),
          )),
        ],
      ),
    );
  }
}