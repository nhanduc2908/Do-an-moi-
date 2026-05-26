import 'package:flutter/material.dart';

class DevicesScreen extends StatelessWidget {
  const DevicesScreen({super.key});

  final List<Map<String, dynamic>> _devices = const [
    {'name': 'iPhone 15 Pro', 'type': 'Mobile', 'lastActive': '2024-01-15 10:30', 'current': true},
    {'name': 'MacBook Pro', 'type': 'Desktop', 'lastActive': '2024-01-14 18:45', 'current': false},
    {'name': 'iPad Air', 'type': 'Tablet', 'lastActive': '2024-01-13 09:20', 'current': false},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Devices'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _devices.length,
        itemBuilder: (context, index) {
          final device = _devices[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: device['current'] ? Colors.green : Colors.grey,
                child: Icon(
                  device['type'] == 'Mobile' ? Icons.phone_android : Icons.computer,
                  color: Colors.white,
                ),
              ),
              title: Text(device['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Last active: ${device['lastActive']}'),
              trailing: device['current']
                  ? const Chip(label: Text('Current'), backgroundColor: Colors.green)
                  : IconButton(
                      icon: const Icon(Icons.logout, color: Colors.red),
                      onPressed: () {},
                    ),
            ),
          );
        },
      ),
    );
  }
}