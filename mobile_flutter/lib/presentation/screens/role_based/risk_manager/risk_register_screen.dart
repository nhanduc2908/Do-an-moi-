import 'package:flutter/material.dart';

class RiskRegisterScreen extends StatefulWidget {
  const RiskRegisterScreen({super.key});

  @override
  State<RiskRegisterScreen> createState() => _RiskRegisterScreenState();
}

class _RiskRegisterScreenState extends State<RiskRegisterScreen> {
  final List<Map<String, dynamic>> _risks = [
    {'id': 'RISK-001', 'name': 'Data Breach', 'category': 'Security', 'likelihood': 4, 'impact': 5, 'score': 20, 'status': 'Active', 'owner': 'Security Team'},
    {'id': 'RISK-002', 'name': 'System Outage', 'category': 'Operational', 'likelihood': 3, 'impact': 4, 'score': 12, 'status': 'Monitoring', 'owner': 'IT Team'},
    {'id': 'RISK-003', 'name': 'Compliance Failure', 'category': 'Compliance', 'likelihood': 2, 'impact': 5, 'score': 10, 'status': 'Active', 'owner': 'Compliance Team'},
    {'id': 'RISK-004', 'name': 'Third-party Breach', 'category': 'Vendor', 'likelihood': 3, 'impact': 4, 'score': 12, 'status': 'Mitigated', 'owner': 'Procurement'},
    {'id': 'RISK-005', 'name': 'Insider Threat', 'category': 'Security', 'likelihood': 2, 'impact': 4, 'score': 8, 'status': 'Active', 'owner': 'HR'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Risk Register'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () => _showAddRiskDialog(),
          ),
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _risks.length,
        itemBuilder: (context, index) {
          final risk = _risks[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: _getScoreColor(risk['score']),
                child: Text(risk['score'].toString(), style: const TextStyle(color: Colors.white, fontSize: 12)),
              ),
              title: Text(risk['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${risk['id']} • ${risk['category']} • Owner: ${risk['owner']}'),
              trailing: Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: _getStatusColor(risk['status']).withOpacity(0.2),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(
                  risk['status'],
                  style: TextStyle(color: _getStatusColor(risk['status']), fontSize: 12),
                ),
              ),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Risk Assessment:', style: TextStyle(fontWeight: FontWeight.bold)),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          Expanded(
                            child: Column(
                              children: [
                                const Text('Likelihood'),
                                const SizedBox(height: 4),
                                Text('${risk['likelihood']}/5', style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                              ],
                            ),
                          ),
                          Expanded(
                            child: Column(
                              children: [
                                const Text('Impact'),
                                const SizedBox(height: 4),
                                Text('${risk['impact']}/5', style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                              ],
                            ),
                          ),
                          Expanded(
                            child: Column(
                              children: [
                                const Text('Risk Score'),
                                const SizedBox(height: 4),
                                Text('${risk['score']}/25', style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: _getScoreColor(risk['score']))),
                              ],
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      const Text('Mitigation Plan:', style: TextStyle(fontWeight: FontWeight.bold)),
                      const Text('Implement additional security controls and monitoring.'),
                      const SizedBox(height: 16),
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

  void _showAddRiskDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Add New Risk'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                decoration: const InputDecoration(labelText: 'Risk Name'),
              ),
              const SizedBox(height: 12),
              DropdownButtonFormField<String>(
                items: const [
                  DropdownMenuItem(value: 'Security', child: Text('Security')),
                  DropdownMenuItem(value: 'Operational', child: Text('Operational')),
                  DropdownMenuItem(value: 'Compliance', child: Text('Compliance')),
                  DropdownMenuItem(value: 'Vendor', child: Text('Vendor')),
                ],
                onChanged: (value) {},
                decoration: const InputDecoration(labelText: 'Category'),
              ),
              const SizedBox(height: 12),
              Row(
                children: [
                  Expanded(
                    child: DropdownButtonFormField<int>(
                      items: List.generate(5, (i) => DropdownMenuItem(value: i + 1, child: Text('${i + 1}'))),
                      onChanged: (value) {},
                      decoration: const InputDecoration(labelText: 'Likelihood'),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: DropdownButtonFormField<int>(
                      items: List.generate(5, (i) => DropdownMenuItem(value: i + 1, child: Text('${i + 1}'))),
                      onChanged: (value) {},
                      decoration: const InputDecoration(labelText: 'Impact'),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              TextField(
                decoration: const InputDecoration(labelText: 'Owner'),
              ),
            ],
          ),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancel')),
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Add')),
        ],
      ),
    );
  }

  Color _getScoreColor(int score) {
    if (score >= 15) return Colors.red;
    if (score >= 8) return Colors.orange;
    return Colors.green;
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Active': return Colors.orange;
      case 'Monitoring': return Colors.blue;
      case 'Mitigated': return Colors.green;
      default: return Colors.grey;
    }
  }
}