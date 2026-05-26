// Đường dẫn: mobile_flutter/lib/presentation/screens/security/container_security_screen.dart

import 'package:flutter/material.dart';

class ContainerSecurityScreen extends StatelessWidget {
  const ContainerSecurityScreen({super.key});

  final List<Map<String, dynamic>> _containers = const [
    {'name': 'web-app', 'image': 'nginx:latest', 'vulnerabilities': 3, 'status': 'Warning', 'running': true},
    {'name': 'api-server', 'image': 'node:18', 'vulnerabilities': 5, 'status': 'Critical', 'running': true},
    {'name': 'database', 'image': 'postgres:15', 'vulnerabilities': 1, 'status': 'Secure', 'running': true},
    {'name': 'redis-cache', 'image': 'redis:7', 'vulnerabilities': 0, 'status': 'Secure', 'running': false},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Container Security'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _containers.length,
        itemBuilder: (context, index) {
          final container = _containers[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: _getStatusColor(container['status']),
                child: const Icon(Icons.docker, color: Colors.white),
              ),
              title: Text(container['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Image: ${container['image']} • Running: ${container['running'] ? 'Yes' : 'No'}'),
              trailing: Chip(
                label: Text('${container['vulnerabilities']} vulns'),
                backgroundColor: _getSeverityColor(container['vulnerabilities']),
              ),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _buildDetailRow('Status', container['status']),
                      _buildDetailRow('Vulnerabilities', container['vulnerabilities'].toString()),
                      _buildDetailRow('Image', container['image']),
                      _buildDetailRow('Running', container['running'] ? 'Yes' : 'No'),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          Expanded(
                            child: OutlinedButton(
                              onPressed: () {},
                              child: const Text('View Details'),
                            ),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: ElevatedButton(
                              onPressed: () {},
                              child: const Text('Scan Container'),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          SizedBox(width: 100, child: Text(label, style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey))),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Secure': return Colors.green;
      case 'Warning': return Colors.orange;
      case 'Critical': return Colors.red;
      default: return Colors.grey;
    }
  }

  Color _getSeverityColor(int count) {
    if (count >= 5) return Colors.red;
    if (count >= 3) return Colors.orange;
    return Colors.green;
  }
}