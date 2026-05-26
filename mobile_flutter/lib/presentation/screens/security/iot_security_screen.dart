// Đường dẫn: mobile_flutter/lib/presentation/screens/security/iot_security_screen.dart

import 'package:flutter/material.dart';

class IotSecurityScreen extends StatelessWidget {
  const IotSecurityScreen({super.key});

  final List<Map<String, dynamic>> _devices = const [
    {'name': 'Smart Camera', 'type': 'Camera', 'firmware': 'v2.1.0', 'status': 'Secure', 'ip': '192.168.1.50', 'lastSeen': '2024-01-15'},
    {'name': 'Smart Lock', 'type': 'Lock', 'firmware': 'v1.0.3', 'status': 'Warning', 'ip': '192.168.1.51', 'lastSeen': '2024-01-14'},
    {'name': 'Temperature Sensor', 'type': 'Sensor', 'firmware': 'v3.2.1', 'status': 'Secure', 'ip': '192.168.1.52', 'lastSeen': '2024-01-15'},
    {'name': 'Smart Plug', 'type': 'Outlet', 'firmware': 'v1.2.0', 'status': 'Critical', 'ip': '192.168.1.53', 'lastSeen': '2024-01-13'},
    {'name': 'Doorbell', 'type': 'Camera', 'firmware': 'v2.0.5', 'status': 'Warning', 'ip': '192.168.1.54', 'lastSeen': '2024-01-15'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('IoT Security'),
        actions: [
          IconButton(
            icon: const Icon(Icons.devices),
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
                backgroundColor: _getStatusColor(device['status']),
                child: const Icon(Icons.devices, color: Colors.white),
              ),
              title: Text(device['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Type: ${device['type']} • IP: ${device['ip']}'),
              trailing: Chip(
                label: Text(device['status']),
                backgroundColor: _getStatusColor(device['status']).withOpacity(0.2),
              ),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _buildDetailRow('Firmware Version', device['firmware']),
                      _buildDetailRow('IP Address', device['ip']),
                      _buildDetailRow('Last Seen', device['lastSeen']),
                      _buildDetailRow('Status', device['status']),
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

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Secure': return Colors.green;
      case 'Warning': return Colors.orange;
      case 'Critical': return Colors.red;
      default: return Colors.grey;
    }
  }
}