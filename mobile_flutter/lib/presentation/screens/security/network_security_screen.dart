import 'package:flutter/material.dart';

class NetworkSecurityScreen extends StatelessWidget {
  const NetworkSecurityScreen({super.key});

  final List<Map<String, dynamic>> _devices = const [
    {'name': 'Router', 'ip': '192.168.1.1', 'status': 'Secure', 'risk': 'Low'},
    {'name': 'Server', 'ip': '192.168.1.100', 'status': 'Warning', 'risk': 'Medium'},
    {'name': 'Workstation', 'ip': '192.168.1.50', 'status': 'Secure', 'risk': 'Low'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Network Security'),
      ),
      body: ListView(
        children: [
          Card(
            margin: const EdgeInsets.all(16),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  const Text('Network Map', style: TextStyle(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  Container(
                    height: 200,
                    decoration: BoxDecoration(
                      border: Border.all(color: Colors.grey.shade300),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: const Center(child: Text('Network Topology Visualization')),
                  ),
                ],
              ),
            ),
          ),
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 16),
            child: Text('Connected Devices', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          ),
          ..._devices.map((device) => Card(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: ListTile(
              leading: const Icon(Icons.devices, color: Colors.blue),
              title: Text(device['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('IP: ${device['ip']}'),
              trailing: Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: device['status'] == 'Secure' ? Colors.green.shade100 : Colors.orange.shade100,
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(device['status']),
              ),
            ),
          )),
          const SizedBox(height: 16),
          ElevatedButton(
            onPressed: () {},
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.blue,
              margin: const EdgeInsets.all(16),
            ),
            child: const Text('Run Network Scan'),
          ),
        ],
      ),
    );
  }
}