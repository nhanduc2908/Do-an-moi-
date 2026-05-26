import 'package:flutter/material.dart';

class CloudSecurityScreen extends StatelessWidget {
  const CloudSecurityScreen({super.key});

  final List<Map<String, dynamic>> _resources = const [
    {'name': 'S3 Bucket', 'provider': 'AWS', 'region': 'us-east-1', 'public': false, 'encrypted': true},
    {'name': 'VM Instance', 'provider': 'AWS', 'region': 'us-west-2', 'public': true, 'encrypted': false},
    {'name': 'Database', 'provider': 'Azure', 'region': 'eastus', 'public': false, 'encrypted': true},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Cloud Security'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _resources.length,
        itemBuilder: (context, index) {
          final resource = _resources[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: resource['public'] ? Colors.red : Colors.green,
                child: const Icon(Icons.cloud, color: Colors.white),
              ),
              title: Text(resource['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Provider: ${resource['provider']} • Region: ${resource['region']}'),
              trailing: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  if (resource['public']) const Icon(Icons.public, color: Colors.red),
                  if (!resource['encrypted']) const Icon(Icons.lock_open, color: Colors.orange),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}