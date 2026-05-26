// Đường dẫn: mobile_flutter/lib/presentation/screens/profile/activity_log_screen.dart

import 'package:flutter/material.dart';

class ActivityLogScreen extends StatelessWidget {
  const ActivityLogScreen({super.key});

  final List<Map<String, dynamic>> _activities = const [
    {'action': 'Login', 'time': '2024-01-15 10:30:00', 'ip': '192.168.1.1', 'status': 'Success'},
    {'action': 'Password Change', 'time': '2024-01-14 15:20:00', 'ip': '192.168.1.1', 'status': 'Success'},
    {'action': 'Login Failed', 'time': '2024-01-13 08:45:00', 'ip': '203.0.113.45', 'status': 'Failed'},
    {'action': 'Profile Update', 'time': '2024-01-12 11:30:00', 'ip': '192.168.1.1', 'status': 'Success'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Activity Log'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _activities.length,
        itemBuilder: (context, index) {
          final activity = _activities[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 8),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: activity['status'] == 'Success' ? Colors.green : Colors.red,
                child: Icon(activity['status'] == 'Success' ? Icons.check : Icons.close, color: Colors.white),
              ),
              title: Text(activity['action'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('IP: ${activity['ip']} • ${activity['time']}'),
              trailing: Chip(
                label: Text(activity['status']),
                backgroundColor: activity['status'] == 'Success' ? Colors.green.shade100 : Colors.red.shade100,
              ),
            ),
          );
        },
      ),
    );
  }
}