import React from 'react'
import {Line} from 'react-chartjs-2'

const lineDatasets = {
  fill: false,
  lineTension: 0.5,
  borderWidth: 1,
  cubicInterpolationMode: 'monotone',
  pointBackgroundColor: '#fff',
  pointBorderWidth: 1,
  pointHoverRadius: 5,
  pointHoverBorderColor: 'rgba(220,220,220,1)',
  pointHoverBorderWidth: 2,
  pointRadius: 0,
  pointHitRadius: 10
};

const lineOptions = {
  scales: {
    xAxes: [
      {
        display: false
      }
    ],
  },
  legend: {
    display: false
  }
};

class LineChart extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      data: {
        labels: this.props.labels,
        datasets: [
          Object.assign({}, lineDatasets, this.props.datasets, {
            label: this.props.label,
            backgroundColor: this.props.color,
            borderColor: this.props.color,
            pointBorderColor: this.props.color,
            pointHoverBackgroundColor: this.props.color,
            data: this.props.data
          })
        ]
      },
      options: Object.assign({}, lineOptions, this.props.options)
    };
  }

  render() {
    return <Line data={this.state.data} options={this.state.options} />
  }
}

LineChart.propTypes = {
  label: React.PropTypes.string.isRequired,
  labels: React.PropTypes.array.isRequired,
  data: React.PropTypes.array.isRequired,
  datasets: React.PropTypes.object,
  color: React.PropTypes.string,
  options: React.PropTypes.object
};

LineChart.defaultProps = {
  color: 'rgba(75,192,192,0.4)',
  datasets: {},
  options: {}
};

export {LineChart};