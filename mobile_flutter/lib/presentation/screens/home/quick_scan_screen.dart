import 'package:flutter/material.dart';

class QuickScanScreen extends StatefulWidget {
  const QuickScanScreen({super.key});

  @override
  State<QuickScanScreen> createState() => _QuickScanScreenState();
}

class _QuickScanScreenState extends State<QuickScanScreen> {
  final TextEditingController _targetController = TextEditingController();
  String _scanType = 'Web';
  bool _isScanning = false;
  List<Map<String, dynamic>> _results = [];

  Future<void> _startScan() async {
    if (_targetController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter a target')),
      );
      return;
    }

    setState(() => _isScanning = true);
    
    await Future.delayed(const Duration(seconds: 3));
    
    setState(() {
      _isScanning = false;
      _results = [
        {'type': 'Vulnerability', 'name': 'Open Port 80', 'severity': 'Medium'},
        {'type': 'Misconfiguration', 'name': 'Missing Security Headers', 'severity': 'High'},
        {'type': 'Info', 'name': 'SSL Certificate Valid', 'severity': 'Low'},
      ];
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Quick Scan'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    TextField(
                      controller: _targetController,
                      decoration: const InputDecoration(
                        labelText: 'Target (URL/IP/Domain)',
                        border: OutlineInputBorder(),
                        prefixIcon: Icon(Icons.link),
                      ),
                    ),
                    const SizedBox(height: 16),
                    Row(
                      children: [
                        const Text('Scan Type:'),
                        const SizedBox(width: 16),
                        Expanded(
                          child: SegmentedButton<String>(
                            segments: const [
                              ButtonSegment(value: 'Web', label: Text('Web')),
                              ButtonSegment(value: 'Network', label: Text('Network')),
                              ButtonSegment(value: 'Vulnerability', label: Text('Vulnerability')),
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
            if (_results.isNotEmpty) ...[
              const SizedBox(height: 16),
              const Text('Scan Results', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              const SizedBox(height: 8),
              ..._results.map((result) => Card(
                margin: const EdgeInsets.only(bottom: 8),
                child: ListTile(
                  leading: CircleAvatar(
                    backgroundColor: result['severity'] == 'High' ? Colors.red : 
                                   (result['severity'] == 'Medium' ? Colors.orange : Colors.green),
                    child: const Icon(Icons.warning, color: Colors.white),
                  ),
                  title: Text(result['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                  subtitle: Text(result['type']),
                  trailing: Chip(label: Text(result['severity'])),
                ),
              )),
            ],
          ],
        ),
      ),
    );
  }
}