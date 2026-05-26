// Đường dẫn: mobile_flutter/lib/presentation/screens/security/mobile_security_screen.dart

import 'package:flutter/material.dart';

class MobileSecurityScreen extends StatelessWidget {
  const MobileSecurityScreen({super.key});

  final List<Map<String, dynamic>> _devices = const [
    {'name': 'iPhone 15 Pro', 'os': 'iOS 17.2', 'jailbroken': false, 'encrypted': true, 'version': '17.2', 'lastScan': '2024-01-15'},
    {'name': 'Samsung Galaxy S24', 'os': 'Android 14', 'jailbroken': true, 'encrypted': false, 'version': '14', 'lastScan': '2024-01-14'},
    {'name': 'iPad Air', 'os': 'iOS 17.1', 'jailbroken': false, 'encrypted': true, 'version': '17.1', 'lastScan': '2024-01-13'},
    {'name': 'Google Pixel 8', 'os': 'Android 14', 'jailbroken': false, 'encrypted': true, 'version': '14', 'lastScan': '2024-01-12'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Mobile Security'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _devices.length,
        itemBuilder: (context, index) {
          final device = _devices[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: device['jailbroken'] ? Colors.red : Colors.green,
                child: const Icon(Icons.phone_android, color: Colors.white),
              ),
              title: Text(device['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('OS: ${device['os']} • Version: ${device['version']}'),
              trailing: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  if (device['jailbroken']) const Icon(Icons.warning, color: Colors.red, size: 20),
                  if (!device['encrypted']) const Icon(Icons.lock_open, color: Colors.orange, size: 20),
                  if (!device['jailbroken'] && device['encrypted']) const Icon(Icons.check_circle, color: Colors.green, size: 20),
                ],
              ),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _buildDetailRow('OS Version', device['version']),
                      _buildDetailRow('Jailbroken/Rooted', device['jailbroken'] ? 'Yes' : 'No'),
                      _buildDetailRow('Encryption', device['encrypted'] ? 'Enabled' : 'Disabled'),
                      _buildDetailRow('Last Scan', device['lastScan']),
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
                              child: const Text('Scan Device'),
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
}