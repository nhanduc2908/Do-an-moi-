import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../data/models/incident_model.dart';
import '../../../providers/incident_provider.dart';
import '../../widgets/common/custom_button.dart';

class IncidentDetailScreen extends ConsumerStatefulWidget {
  const IncidentDetailScreen({super.key, required this.incidentId});
  final String incidentId;

  @override
  ConsumerState<IncidentDetailScreen> createState() => _IncidentDetailScreenState();
}

class _IncidentDetailScreenState extends ConsumerState<IncidentDetailScreen> {
  @override
  void initState() {
    super.initState();
    ref.read(incidentProvider.notifier).loadIncidentDetail(widget.incidentId);
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(incidentProvider);
    final incident = state.currentIncident;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Incident Details'),
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : incident == null
              ? const Center(child: Text('Incident not found'))
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            children: [
                              Row(
                                children: [
                                  CircleAvatar(
                                    backgroundColor: _getSeverityColor(incident.severity ?? 'medium'),
                                    child: const Icon(Icons.warning, color: Colors.white),
                                  ),
                                  const SizedBox(width: 16),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          incident.title ?? 'Unknown',
                                          style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                                        ),
                                        Text('ID: ${incident.incidentCode}'),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                              const Divider(),
                              _buildDetailRow('Severity', incident.severity?.toUpperCase() ?? 'N/A'),
                              _buildDetailRow('Status', incident.status ?? 'N/A'),
                              _buildDetailRow('Category', incident.category ?? 'N/A'),
                              _buildDetailRow('Detected', _formatDate(incident.detectedAt)),
                              _buildDetailRow('Reported', _formatDate(incident.reportedAt)),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Description', style: TextStyle(fontWeight: FontWeight.bold)),
                              const SizedBox(height: 8),
                              Text(incident.description ?? 'No description'),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
                      Row(
                        children: [
                          Expanded(
                            child: CustomButton(
                              text: 'Add Comment',
                              onPressed: () {},
                              isOutlined: true,
                            ),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: CustomButton(
                              text: 'Update Status',
                              onPressed: () {},
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          SizedBox(width: 80, child: Text(label, style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey))),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }

  String _formatDate(DateTime? date) {
    if (date == null) return 'N/A';
    return '${date.day}/${date.month}/${date.year} ${date.hour}:${date.minute}';
  }

  Color _getSeverityColor(String severity) {
    switch (severity.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      case 'medium': return Colors.yellow;
      default: return Colors.green;
    }
  }
}