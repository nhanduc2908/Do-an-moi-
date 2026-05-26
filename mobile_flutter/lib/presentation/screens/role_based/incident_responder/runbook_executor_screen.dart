import 'package:flutter/material.dart';

class RunbookExecutorScreen extends StatefulWidget {
  const RunbookExecutorScreen({super.key});

  @override
  State<RunbookExecutorScreen> createState() => _RunbookExecutorScreenState();
}

class _RunbookExecutorScreenState extends State<RunbookExecutorScreen> {
  final List<Map<String, dynamic>> _runbooks = [
    {'name': 'Ransomware Response', 'category': 'Malware', 'steps': 8, 'duration': '15 min', 'status': 'Ready'},
    {'name': 'Data Breach', 'category': 'Breach', 'steps': 12, 'duration': '30 min', 'status': 'Ready'},
    {'name': 'DDoS Mitigation', 'category': 'Network', 'steps': 6, 'duration': '10 min', 'status': 'Ready'},
    {'name': 'Phishing Incident', 'category': 'Social', 'steps': 5, 'duration': '8 min', 'status': 'Ready'},
    {'name': 'Malware Outbreak', 'category': 'Malware', 'steps': 10, 'duration': '20 min', 'status': 'Draft'},
  ];

  void _executeRunbook(Map<String, dynamic> runbook) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Execute ${runbook['name']}'),
        content: const Text('This will start the automated incident response process. Do you want to continue?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancel')),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(content: Text('Executing ${runbook['name']}...')),
              );
            },
            child: const Text('Execute'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Runbook Executor'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _runbooks.length,
        itemBuilder: (context, index) {
          final runbook = _runbooks[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: _getCategoryColor(runbook['category']),
                child: Text(runbook['category'].substring(0, 1), style: const TextStyle(color: Colors.white)),
              ),
              title: Text(runbook['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${runbook['steps']} steps • ${runbook['duration']}'),
              trailing: Chip(
                label: Text(runbook['status']),
                backgroundColor: runbook['status'] == 'Ready' ? Colors.green.shade100 : Colors.grey.shade200,
              ),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Runbook Steps:', style: TextStyle(fontWeight: FontWeight.bold)),
                      const SizedBox(height: 8),
                      ...List.generate(runbook['steps'], (i) => Padding(
                        padding: const EdgeInsets.symmetric(vertical: 4),
                        child: Row(
                          children: [
                            const Icon(Icons.check_circle_outline, size: 16, color: Colors.green),
                            const SizedBox(width: 8),
                            Text('Step ${i + 1}: ${_getStepDescription(runbook['name'], i)}'),
                          ],
                        ),
                      )),
                      const SizedBox(height: 16),
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton(
                          onPressed: runbook['status'] == 'Ready' ? () => _executeRunbook(runbook) : null,
                          style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
                          child: const Text('Execute Runbook'),
                        ),
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

  Color _getCategoryColor(String category) {
    switch (category) {
      case 'Malware': return Colors.red;
      case 'Breach': return Colors.orange;
      case 'Network': return Colors.blue;
      case 'Social': return Colors.purple;
      default: return Colors.grey;
    }
  }

  String _getStepDescription(String runbook, int step) {
    const descriptions = {
      'Ransomware Response': ['Isolate affected systems', 'Identify ransomware variant', 'Contain spread', 'Remove malware', 'Restore from backup', 'Patch vulnerabilities', 'Review security controls', 'Document incident'],
      'Data Breach': ['Identify breach source', 'Contain breach', 'Preserve evidence', 'Notify stakeholders', 'Investigate impact', 'Remediate vulnerabilities', 'Update security policies', 'Notify regulators', 'Communicate with customers', 'Review response', 'Document findings', 'Implement improvements'],
    };
    return descriptions[runbook]?[step] ?? 'Execute step ${step + 1}';
  }
}