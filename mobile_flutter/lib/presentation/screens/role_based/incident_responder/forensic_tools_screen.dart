import 'package:flutter/material.dart';

class ForensicToolsScreen extends StatefulWidget {
  const ForensicToolsScreen({super.key});

  @override
  State<ForensicToolsScreen> createState() => _ForensicToolsScreenState();
}

class _ForensicToolsScreenState extends State<ForensicToolsScreen> {
  final List<Map<String, dynamic>> _tools = [
    {'name': 'Memory Analyzer', 'type': 'Analysis', 'status': 'Available', 'icon': Icons.memory},
    {'name': 'Disk Imager', 'type': 'Acquisition', 'status': 'Available', 'icon': Icons.storage},
    {'name': 'Log Parser', 'type': 'Analysis', 'status': 'Available', 'icon': Icons.description},
    {'name': 'Network Forensics', 'type': 'Network', 'status': 'Available', 'icon': Icons.network_check},
    {'name': 'Timeline Builder', 'type': 'Analysis', 'status': 'Processing', 'icon': Icons.timeline},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Forensic Tools'),
      ),
      body: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.grey.shade100,
            child: Row(
              children: [
                Expanded(
                  child: TextField(
                    decoration: InputDecoration(
                      hintText: 'Enter system ID or IP...',
                      prefixIcon: const Icon(Icons.search),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8),
                        backgroundColor: Colors.white,
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                ElevatedButton(
                  onPressed: () {},
                  child: const Text('Collect'),
                ),
              ],
            ),
          ),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _tools.length,
              itemBuilder: (context, index) {
                final tool = _tools[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: ListTile(
                    leading: Container(
                      width: 50,
                      height: 50,
                      decoration: BoxDecoration(
                        color: _getTypeColor(tool['type']).withOpacity(0.1),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Icon(tool['icon'], color: _getTypeColor(tool['type']), size: 28),
                    ),
                    title: Text(tool['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Text(tool['type']),
                    trailing: Chip(
                      label: Text(tool['status']),
                      backgroundColor: tool['status'] == 'Available' ? Colors.green.shade100 : Colors.orange.shade100,
                    ),
                    onTap: () {},
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Color _getTypeColor(String type) {
    switch (type) {
      case 'Analysis': return Colors.blue;
      case 'Acquisition': return Colors.green;
      case 'Network': return Colors.orange;
      default: return Colors.purple;
    }
  }
}