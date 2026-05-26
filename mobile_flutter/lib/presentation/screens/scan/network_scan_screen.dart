// Đường dẫn: mobile_flutter/lib/presentation/screens/scan/network_scan_screen.dart

import 'package:flutter/material.dart';

class NetworkScanScreen extends StatefulWidget {
  const NetworkScanScreen({super.key});

  @override
  State<NetworkScanScreen> createState() => _NetworkScanScreenState();
}

class _NetworkScanScreenState extends State<NetworkScanScreen> {
  final TextEditingController _targetController = TextEditingController();
  bool _isScanning = false;
  List<Map<String, dynamic>> _results = [];
  String _scanType = 'Quick';

  Future<void> _startScan() async {
    if (_targetController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter target IP or range')),
      );
      return;
    }

    setState(() {
      _isScanning = true;
      _results = [];
    });

    await Future.delayed(const Duration(seconds: 3));

    setState(() {
      _isScanning = false;
      _results = [
        {'ip': '192.168.1.1', 'hostname': 'router.local', 'status': 'up', 'ports': [80, 443, 22]},
        {'ip': '192.168.1.10', 'hostname': 'server.local', 'status': 'up', 'ports': [80, 443, 3306, 22]},
        {'ip': '192.168.1.20', 'hostname': 'workstation-01', 'status': 'up', 'ports': [80, 445]},
        {'ip': '192.168.1.30', 'hostname': 'printer.local', 'status': 'down', 'ports': []},
      ];
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Network Scan'),
        actions: [
          IconButton(
            icon: const Icon(Icons.settings),
            onPressed: () {},
          ),
        ],
      ),
      body: Column(
        children: [
          Card(
            margin: const EdgeInsets.all(16),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: TextField(
                          controller: _targetController,
                          decoration: const InputDecoration(
                            labelText: 'Target (IP/CIDR)',
                            hintText: 'e.g., 192.168.1.0/24',
                            border: OutlineInputBorder(),
                            prefixIcon: Icon(Icons.network_check),
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      const Text('Scan Type:'),
                      const SizedBox(width: 16),
                      Expanded(
                        child: SegmentedButton<String>(
                          segments: const [
                            ButtonSegment(value: 'Quick', label: Text('Quick')),
                            ButtonSegment(value: 'Full', label: Text('Full')),
                            ButtonSegment(value: 'Port', label: Text('Port Scan')),
                          ],
                          selected: {_scanType},
                          onSelectionChanged: (Set<String> selection) {
                            setState(() => _scanType = selection.first);
                          },
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: _isScanning ? null : _startScan,
                      child: _isScanning ? const CircularProgressIndicator() : const Text('Start Scan'),
                    ),
                  ),
                ],
              ),
            ),
          ),
          if (_isScanning)
            const Padding(
              padding: EdgeInsets.all(16),
              child: Column(
                children: [
                  CircularProgressIndicator(),
                  SizedBox(height: 16),
                  Text('Scanning network...'),
                ],
              ),
            ),
          if (_results.isNotEmpty)
            Expanded(
              child: ListView.builder(
                padding: const EdgeInsets.all(16),
                itemCount: _results.length,
                itemBuilder: (context, index) {
                  final device = _results[index];
                  return Card(
                    margin: const EdgeInsets.only(bottom: 8),
                    child: ExpansionTile(
                      leading: CircleAvatar(
                        backgroundColor: device['status'] == 'up' ? Colors.green : Colors.red,
                        child: const Icon(Icons.devices, color: Colors.white),
                      ),
                      title: Text(device['hostname'], style: const TextStyle(fontWeight: FontWeight.bold)),
                      subtitle: Text(device['ip']),
                      trailing: Chip(
                        label: Text(device['status']),
                        backgroundColor: device['status'] == 'up' ? Colors.green.shade100 : Colors.red.shade100,
                      ),
                      children: [
                        Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Open Ports:', style: TextStyle(fontWeight: FontWeight.bold)),
                              const SizedBox(height: 8),
                              Wrap(
                                spacing: 8,
                                children: device['ports'].map<Widget>((port) => Chip(
                                  label: Text(port.toString()),
                                  backgroundColor: Colors.blue.shade100,
                                )).toList(),
                              ),
                              const SizedBox(height: 12),
                              ElevatedButton(
                                onPressed: () {},
                                child: const Text('View Details'),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  );
                },
              ),
            ),
        ],
      ),
    );
  }
}