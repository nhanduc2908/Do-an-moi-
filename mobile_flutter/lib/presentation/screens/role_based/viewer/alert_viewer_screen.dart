import 'package:flutter/material.dart';

class AlertViewerScreen extends StatefulWidget {
  const AlertViewerScreen({super.key});

  @override
  State<AlertViewerScreen> createState() => _AlertViewerScreenState();
}

class _AlertViewerScreenState extends State<AlertViewerScreen> {
  final List<Map<String, dynamic>> _alerts = [
    {'severity': 'Critical', 'title': 'Security Breach Detected', 'time': '5 minutes ago', 'status': 'Active'},
    {'severity': 'High', 'title': 'Suspicious Login Activity', 'time': '15 minutes ago', 'status': 'Investigating'},
    {'severity': 'Medium', 'title': 'Unusual Network Traffic', 'time': '1 hour ago', 'status': 'Monitoring'},
    {'severity': 'Low', 'title': 'Failed Login Attempt', 'time': '2 hours ago', 'status': 'Resolved'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Alert Viewer'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _alerts.length,
        itemBuilder: (context, index) {
          final alert = _alerts[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: _getSeverityColor(alert['severity']),
                child: Icon(_getSeverityIcon(alert['severity']), color: Colors.white),
              ),
              title: Text(alert['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${alert['time']} • ${alert['status']}'),
              trailing: Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: _getSeverityColor(alert['severity']).withOpacity(0.2),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(
                  alert['severity'],
                  style: TextStyle(color: _getSeverityColor(alert['severity']), fontSize: 12),
                ),
              ),
              onTap: () {},
            ),
          );
        },
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

  IconData _getSeverityIcon(String severity) {
    switch (severity) {
      case 'Critical': return Icons.error;
      case 'High': return Icons.warning;
      default: return Icons.info;
    }
  }
}