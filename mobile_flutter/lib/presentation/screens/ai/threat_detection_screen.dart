import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

class ThreatDetectionScreen extends ConsumerStatefulWidget {
  const ThreatDetectionScreen({super.key});

  @override
  ConsumerState<ThreatDetectionScreen> createState() => _ThreatDetectionScreenState();
}

class _ThreatDetectionScreenState extends ConsumerState<ThreatDetectionScreen> {
  final List<Map<String, dynamic>> _threats = [
    {'type': 'Malware', 'source': '192.168.1.100', 'confidence': 95, 'time': '2024-01-15 10:30:00', 'status': 'Active'},
    {'type': 'Phishing', 'source': 'example.com', 'confidence': 87, 'time': '2024-01-15 09:45:00', 'status': 'Investigating'},
    {'type': 'DDoS', 'source': '203.0.113.45', 'confidence': 92, 'time': '2024-01-15 08:20:00', 'status': 'Contained'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Threat Detection'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _threats.length,
        itemBuilder: (context, index) {
          final threat = _threats[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: _getThreatColor(threat['confidence']),
                child: const Icon(Icons.warning, color: Colors.white),
              ),
              title: Text(threat['type'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Source: ${threat['source']} • Confidence: ${threat['confidence']}%'),
              trailing: Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: _getStatusColor(threat['status']).withOpacity(0.2),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(threat['status'], style: TextStyle(color: _getStatusColor(threat['status']))),
              ),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _buildDetailRow('Detection Time', threat['time']),
                      _buildDetailRow('Confidence Score', '${threat['confidence']}%'),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          Expanded(
                            child: OutlinedButton(
                              onPressed: () {},
                              child: const Text('Investigate'),
                            ),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: ElevatedButton(
                              onPressed: () {},
                              child: const Text('Mitigate'),
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

  Color _getThreatColor(int confidence) {
    if (confidence >= 90) return Colors.red;
    if (confidence >= 70) return Colors.orange;
    return Colors.yellow;
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Active': return Colors.red;
      case 'Investigating': return Colors.orange;
      case 'Contained': return Colors.green;
      default: return Colors.grey;
    }
  }
}