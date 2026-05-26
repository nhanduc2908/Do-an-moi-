import 'package:flutter/material.dart';

class ApiSecurityScreen extends StatelessWidget {
  const ApiSecurityScreen({super.key});

  final List<Map<String, dynamic>> _apis = const [
    {'name': 'User API', 'endpoint': '/api/users', 'auth': 'JWT', 'rate_limit': '100/min', 'status': 'Secure'},
    {'name': 'Data API', 'endpoint': '/api/data', 'auth': 'API Key', 'rate_limit': '50/min', 'status': 'Warning'},
    {'name': 'Admin API', 'endpoint': '/api/admin', 'auth': 'OAuth2', 'rate_limit': '20/min', 'status': 'Secure'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('API Security'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _apis.length,
        itemBuilder: (context, index) {
          final api = _apis[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: api['status'] == 'Secure' ? Colors.green : Colors.orange,
                child: const Icon(Icons.api, color: Colors.white),
              ),
              title: Text(api['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Endpoint: ${api['endpoint']} • Auth: ${api['auth']}'),
              trailing: Chip(
                label: Text(api['rate_limit']),
                backgroundColor: Colors.blue.shade100,
              ),
            ),
          );
        },
      ),
    );
  }
}